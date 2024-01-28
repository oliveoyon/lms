<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DependentController;
use App\Http\Controllers\Controller;
use App\Models\Admin\FeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Mpdf\Mpdf;

class TestController extends Controller
{
    public function test1()
    {
        // for ($i=0; $i < 12; $i++) {
        //     $dueDate = now()->addMonths(($i - 1) * 12 / 12 + 1)->startOfMonth()->addDays(19);
        //     echo $dueDate.'<br>';
        // }

        // $pdf = PDF::loadView('idcard');
        // $pdf->output();
        // return $pdf->stream('document.pdf');
        $send['card'] = (object) [
            'name' => 'AYESHA',
            'std_id' => '24001',
            'class' => 'One',
            'dob' => '01/05/2023',
            'blood_group' => 'O+',
            'full_name' => 'AYESHA AFROZ ARIFUR RAHMAN',
            'emergency' => '01712105580',
            'school_contact' => '01712105580',
            'school_name' => 'SHALIKHA THANA HIGH SCHOOL',
            'school_address' => 'Hajrahati, Shalikha, Magura'
        ];

        return view('idcard', $send);
    }

    public function test()
    {

        $send['card'] = (object) [
            'name' => 'A.K.M BODRUDDUZA CHOWDHURY',
            'std_id' => '24001',
            'class' => 'One',
            'dob' => '01/05/2023',
            'blood_group' => 'O+',
            'full_name' => 'AYESHA AFROZ',
            'emergency' => '01712105580',
            'school_contact' => '01712105580',
            'school_name' => 'SHALIKHA THANA HIGH SCHOOL',
            'school_address' => 'Hajrahati, Shalikha, Magura'
        ];

        $cr80Width = 53.98;
        $cr80Height = 85.6;

        // Initialize mPDF with CR80 size in millimeters
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [$cr80Width, $cr80Height], // Set the custom size for CR80 card in portrait
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);
        // $mpdf->SetWatermarkText('SHALIKHA');
        // $mpdf->showWatermarkText = true;
        // $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetWatermarkImage('logo.png');
        $mpdf->showWatermarkImage = true;
        $mpdf->watermarkImageAlpha = 0.1;
        $mpdf->SetAutoPageBreak(true);

        $mpdf->SetAuthor('IconBangla');

        $bladeViewPath = 'idcard';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);
        return $mpdf->Output('yourFileName.pdf', 'I');
    }

    public function test3()
    {
        $currentDate = now();
        $totalDuesByStudent = DB::table('students')->where('students.std_id', 24001)
            ->select(
                'students.std_id',
                'students.std_name',
                'fee_collections.payable_amount',
                'fp.total_amount_paid',
                DB::raw('SUM(COALESCE(fee_collections.payable_amount, 0) - COALESCE(fp.total_amount_paid, 0)) as total_due_amount')
            )
            ->leftJoin('fee_collections', function ($join) use ($currentDate) {
                $join->on('students.std_id', '=', 'fee_collections.std_id')
                    ->where('fee_collections.is_paid', 0) // Unpaid fees
                    ->whereDate('fee_collections.due_date', '>=', $currentDate->format('Y-m-d')); // Due date on or before the current date
            })
            ->leftJoin(DB::raw('(SELECT fee_collection_id, SUM(amount_paid) as total_amount_paid FROM fee_payments GROUP BY fee_collection_id) as fp'), 'fee_collections.id', '=', 'fp.fee_collection_id')
            ->groupBy('students.std_id', 'students.std_name', 'fee_collections.payable_amount', 'fp.total_amount_paid')
            ->get();


        dd($totalDuesByStudent);
    }

    public function test4()
    {
        $trnxId = '1704738425';

        $send['payments'] = DB::table('fee_payments')
            ->select(
                'fee_payments.id',
                'fee_payments.fee_collection_id',
                'fee_payments.amount_paid',
                'fee_payments.payment_date',
                'fee_payments.payment_method',
                'fee_payments.trnx_id',
                'fee_payments.status',
                'fee_payments.school_id',
                'fee_collections.id as fee_collection_id',
                'fee_collections.fee_description',
                'students.std_name',
                'students.std_id',
                'edu_classes.class_name'
            )
            ->join('fee_collections', 'fee_payments.fee_collection_id', '=', 'fee_collections.id')
            ->join('students', 'fee_collections.std_id', '=', 'students.std_id')
            ->join('edu_classes', 'students.class_id', '=', 'edu_classes.id')
            ->where('fee_payments.trnx_id', $trnxId)
            ->get();
        $totalword = $send['payments']->sum('amount_paid');
        $numberService = new DependentController();
        $send['words'] = $numberService->numberToWords($totalword);


        $is_pos = true;
        $width = 0; //should be in database
        $height = 0; // should be in database

        $send['printmeta'] = [
            'fontsize' => 10,
            'width' => 80
        ];

        if ($is_pos) {
            return view('dashboard.admin.feeCollection.fee_invoice_pos', $send);
        } else {

            // $cr80Width = 80; //80 Width in millimeters
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [190, 236], //it will come from database
                // 'width' => 80,
                // 'margin_left' => 5,
                // 'margin_right' => 5,
                // 'margin_top' => 0,
                // 'margin_bottom' => 0,
            ]);

            $mpdf->SetWatermarkText('PAID');
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetAutoPageBreak(true);
            $mpdf->SetAuthor('IconBangla');
            $bladeViewPath = 'dashboard.admin.feeCollection.fee_invoice';
            $html = view($bladeViewPath, $send)->render();
            $mpdf->WriteHTML($html);
            return $mpdf->Output('invoice.pdf', 'I');
        }
    }

    public function test5()
    {

        $academicYear = '2024';
        $classId = 1;
        $sectionId = 1;
        $vserionId = 1;
        $currentMonth = now()->format('m'); // Get the current month

        $dueReports = DB::table('academic_students')
            ->leftJoin('fee_collections', 'academic_students.std_id', '=', 'fee_collections.std_id')
            ->leftJoin('fee_payments', 'fee_collections.id', '=', 'fee_payments.fee_collection_id')
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->join('edu_classes', 'academic_students.class_id', '=', 'edu_classes.id')
            ->join('sections', 'academic_students.section_id', '=', 'sections.id')
            ->join('edu_versions', 'academic_students.version_id', '=', 'edu_versions.id')
            ->where('academic_students.academic_year', '=', $academicYear)
            ->where('academic_students.class_id', '=', $classId)
            ->where('academic_students.section_id', '=', $sectionId)
            ->where('academic_students.version_id', '=', $vserionId)
            ->where('fee_collections.is_paid', '=', false)
            ->whereRaw("MONTH(fee_collections.due_date) <= ?", [$currentMonth]) // Compare with current month
            ->select(
                'academic_students.academic_year',
                'academic_students.std_id',
                'students.std_name',
                'edu_classes.class_name',
                'sections.section_name',
                'edu_versions.version_name',
                DB::raw('SUM(fee_collections.payable_amount) - COALESCE(SUM(fee_payments.amount_paid), 0) as total_due')
            )
            ->groupBy('academic_students.academic_year', 'academic_students.std_id', 'students.std_name', 'edu_classes.class_name', 'sections.section_name', 'edu_versions.version_name')
            ->get();

        dd($dueReports);
    }
}
