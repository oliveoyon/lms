<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\EduClasses;
use App\Models\Admin\Section;
use App\Models\Admin\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DependentController extends Controller
{
    public function getClassesByVersion(Request $request)
    {
        $versionId = $request->input('version_id');
        $classes = EduClasses::where('version_id', $versionId)->get();

        return response()->json(['classes' => $classes]);
    }

    public function fetchStudentsName(Request $request)
    {
        $conditions = [
            'academic_students.academic_year' => $request->input('academic_year'),
            'academic_students.version_id' => $request->input('version_id'),
            'academic_students.class_id' => $request->input('class_id'),
            'academic_students.section_id' => $request->input('section_id'),
            'academic_students.st_aca_status' => 1,
        ];

        $students = AcademicStudent::where($conditions)
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->get(['academic_students.std_id', 'students.std_name']);

        return response()->json(['students' => $students]);
    }


    public function getSectionByClass(Request $request)
    {
        $classId = $request->input('class_id');
        $versionId = $request->input('version_id');

        $sections = Section::where(['class_id' => $classId, 'version_id' => $versionId])->get();
        return response()->json(['sections' => $sections]);
    }

    public function getFeegroupByAcademicYear(Request $request)
    {
        $academic_year = $request->input('academic_year');
        $feegroups = AcademicFeeGroup::where(['academic_year' => $academic_year, 'aca_group_status' => 1])->get();

        return response()->json(['feegroups' => $feegroups]);
    }

    public function getSubjectsByClass(Request $request)
    {
        $classId = $request->input('class_id');
        $versionId = $request->input('version_id');

        // Fetch subjects based on the selected class and version
        $subjects = Subject::where('class_id', $classId)
            ->where('version_id', $versionId)
            ->get();

        return response()->json(['subjects' => $subjects]);
    }

    public function getTotalDues($std_id)
    {
        $currentDate = now()->toDateString();

        $dueAmountByStudent = DB::table('students')
            ->select(
                'students.std_id',
                'students.std_name',
                DB::raw('SUM(fee_collections.payable_amount) - COALESCE(SUM(fp.total_amount_paid), 0) as total_due_amount')
            )
            ->leftJoin('fee_collections', 'students.std_id', '=', 'fee_collections.std_id')
            ->leftJoin(DB::raw('(SELECT fee_collection_id, SUM(amount_paid) as total_amount_paid FROM fee_payments GROUP BY fee_collection_id) as fp'), 'fee_collections.id', '=', 'fp.fee_collection_id')
            ->where('students.std_id', $std_id) // Filter by student ID
            ->where('fee_collections.is_paid', 0) // Unpaid fees
            ->where('fee_collections.due_date', '>', $currentDate) // Future due date
            ->groupBy('students.std_id', 'students.std_name')
            ->having('total_due_amount', '>', 0); // Filter out students with no due amount

        // Display the generated SQL query

        // Execute the query and display the result
        return ($dueAmountByStudent->first());
    }

    public function getDetailedDues($std_id)
    {
        $currentDate = now()->toDateString();

        $receivableReport = DB::table('fee_collections')
            ->select(
                'fee_collections.id as fee_collection_id',
                'students.std_id',
                'students.std_name',
                'fee_collections.payable_amount',
                DB::raw('COALESCE(SUM(fee_payments.amount_paid), 0) as total_amount_paid'),
                'fee_collections.due_date',
                'fee_collections.fee_description'
            )
            ->join('students', 'fee_collections.std_id', '=', 'students.std_id')
            ->leftJoin('fee_payments', 'fee_collections.id', '=', 'fee_payments.fee_collection_id')
            ->where('students.std_id', $std_id) // Filter by student ID
            ->where(function ($query) {
                $query->where('fee_collections.is_paid', 0) // Unpaid fees
                    ->orWhere('fee_payments.amount_paid', '<', 'fee_collections.payable_amount'); // Partially paid fees
            })
            // ->where('fee_collections.due_date', '>', $currentDate) // Future due date
            ->groupBy('fee_collections.id', 'students.std_id', 'students.std_name', 'fee_collections.payable_amount', 'fee_collections.due_date', 'fee_collections.fee_description');


        return ($receivableReport->get());
    }

    public function getDuebyStdasToday($std_id)
    {
        $currentDate = Carbon::parse('2024-02-15');
        $startOfMonth = $currentDate->startOfYear()->format('Y-m-d'); // Start of the year
        $endOfMonth = $currentDate->endOfMonth()->format('Y-m-d');
        $totalDuesByStudent = DB::table('students')
            ->select(
                'students.std_id',
                'students.std_name',
                'fee_collections.*',
                'fp.total_amount_paid',
                DB::raw('SUM(COALESCE(fee_collections.payable_amount, 0) - COALESCE(fp.total_amount_paid, 0)) as total_due_amount')
            )
            ->leftJoin('fee_collections', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('students.std_id', '=', 'fee_collections.std_id')
                    ->where('fee_collections.is_paid', 0) // Unpaid fees
                    ->whereBetween('fee_collections.due_date', [$startOfMonth, $endOfMonth]); // Due date within the specified range
            })
            ->leftJoin(DB::raw('(SELECT fee_collection_id, SUM(amount_paid) as total_amount_paid FROM fee_payments GROUP BY fee_collection_id) as fp'), 'fee_collections.id', '=', 'fp.fee_collection_id')
            ->where('fee_collections.std_id', $std_id)
            ->groupBy('fee_collections.id', 'fee_collections.due_date', 'fee_collections.payable_amount',  'fee_collections.fee_description', 'students.std_id', 'students.std_name')
            ->orderBy('fee_collections.id', 'ASC')
            ->get();

        return $totalDuesByStudent;
    }

    // app/helpers.php

    public function numberToWords($number)
    {
        $ones = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
        );

        $tens = array(
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
        );

        $twenties = array(
            2 => 'Twenty',
            3 => 'Thirty',
            4 => 'Forty',
            5 => 'Fifty',
            6 => 'Sixty',
            7 => 'Seventy',
            8 => 'Eighty',
            9 => 'Ninety',
        );

        $hundreds = array(
            1 => 'One Hundred',
            2 => 'Two Hundred',
            3 => 'Three Hundred',
            4 => 'Four Hundred',
            5 => 'Five Hundred',
            6 => 'Six Hundred',
            7 => 'Seven Hundred',
            8 => 'Eight Hundred',
            9 => 'Nine Hundred',
        );

        if ($number < 0 || $number > 1000000) {
            return 'Number out of range';
        }

        if ($number < 10) {
            return $ones[$number];
        } elseif ($number < 20) {
            return $tens[$number];
        } elseif ($number < 100) {
            $ten = (int)($number / 10);
            $one = $number % 10;

            return $twenties[$ten] . ($one > 0 ? ' ' . $ones[$one] : '');
        } elseif ($number < 1000) {
            $hundred = (int)($number / 100);
            $remainder = $number % 100;

            $result = $hundreds[$hundred] . ($remainder > 0 ? ' and ' . $this->numberToWords($remainder) : '');
        } elseif ($number < 100000) {
            $thousand = (int)($number / 1000);
            $remainder = $number % 1000;

            $result = $this->numberToWords($thousand) . ' Thousand' . ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        } else {
            $hundredThousand = (int)($number / 100000);
            $remainder = $number % 100000;

            $result = $this->numberToWords($hundredThousand) . ' Hundred Thousand' . ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        }

        return $result.' Only';
    }

}
