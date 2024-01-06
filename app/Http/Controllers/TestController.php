<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Mpdf\Mpdf;

class TestController extends Controller
{
    public function testfdf()
    {
        // for ($i=0; $i < 12; $i++) {
        //     $dueDate = now()->addMonths(($i - 1) * 12 / 12 + 1)->startOfMonth()->addDays(19);
        //     echo $dueDate.'<br>';
        // }

        // $pdf = PDF::loadView('idcard');
        // $pdf->output();
        // return $pdf->stream('document.pdf');

        // return view('idcard');



    }

    public function test()
    {

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
        // $mpdf->SetWatermarkImage('logo.png');
        // $mpdf->showWatermarkImage = true;
        // $mpdf->watermarkImageAlpha = 0.05;
        $mpdf->SetAutoPageBreak(true);



        $bladeViewPath = 'idcard';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);
        return $mpdf->Output();
    }




}
