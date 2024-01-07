<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $totalDuesByStudent = DB::table('students')
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
}
