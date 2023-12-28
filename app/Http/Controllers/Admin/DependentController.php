<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\EduClasses;
use App\Models\Admin\Section;
use App\Models\Admin\Subject;
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
            ->where('fee_collections.due_date', '>', $currentDate) // Future due date
            ->groupBy('fee_collections.id', 'students.std_id', 'students.std_name', 'fee_collections.payable_amount', 'fee_collections.due_date', 'fee_collections.fee_description');


        return ($receivableReport->get());
    }
}
