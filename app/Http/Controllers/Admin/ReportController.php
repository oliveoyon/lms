<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function generatePdf(Request $request)
    {
        $send['data'] = $request->input('pdf_data');
        $send['title'] = $request->input('title');
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $request->input('orientation'),
            // 'margin_left' => 5,
            // 'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('IconBangla');

        $bladeViewPath = 'dashboard.admin.reports.common-reports';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);

        // Save the PDF file in the public folder
        $pdfFilePath = public_path('invoice.pdf');
        $mpdf->Output($pdfFilePath, 'F');

        // Construct the public URL of the saved PDF
        $pdfUrl = url('invoice.pdf');

        // Return a JSON response with the PDF URL and a success message
        return response()->json(['pdf_url' => $pdfUrl, 'message' => 'PDF generated successfully']);
    }


    public function classlist_report()
    {
        $send['classes'] = EduClasses::get();
        $send['versions'] = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_list', $send);
    }
}
