<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function version_classlist_report()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.version_class_list', compact('versions'));
    }

    public function versionWiseClassList(Request $request)
    {
        $classes = EduClasses::select('edu_classes.*', 'edu_versions.version_name')
            ->join('edu_versions', 'edu_classes.version_id', '=', 'edu_versions.id')
            ->where('edu_classes.version_id', $request->version_id)
            ->get();
        return response()->json(['classes' => $classes]);
    }

    public function version_enroll()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.version_enroll', compact('versions'));
    }

    public function versionWiseEnrollment(Request $request)
    {
        $versionId = $request->version_id;
        $academicYear = $request->academic_year;

        $classes = DB::table('edu_classes')
            ->select('edu_classes.id', 'edu_classes.class_name', 'edu_versions.version_name', 'academic_students.academic_year')
            ->leftJoin('edu_versions', 'edu_classes.version_id', '=', 'edu_versions.id')
            ->leftJoin('sections', 'edu_classes.id', '=', 'sections.class_id')
            ->leftJoin('academic_students', 'sections.id', '=', 'academic_students.section_id')
            ->selectRaw('edu_classes.id, edu_classes.class_name, edu_versions.version_name, academic_students.academic_year, COUNT(DISTINCT sections.id) as total_sections, COUNT(DISTINCT academic_students.id) as total_students')
            ->where('edu_classes.version_id', $versionId)
            ->where('edu_classes.class_status', 1)
            ->where('sections.section_status', 1)
            ->where('academic_students.academic_year', $academicYear)
            ->groupBy('edu_classes.id', 'edu_classes.class_name', 'edu_versions.version_name', 'academic_students.academic_year')
            ->get();
        return response()->json(['classes' => $classes]);
    }

    public function class_summery(Request $request)
    {
        if ($request->isMethod('post')) {

            $versionId = $request->version_id;
            $academicYear = $request->academic_year;

            $classes = DB::table('sections')
                ->select(
                    'sections.id as section_id',
                    'sections.section_name',
                    'edu_classes.class_name',
                    'sections.version_id',
                    'edu_versions.version_name',
                    'sections.max_students',
                    'sections.class_teacher_id',
                    'teachers.teacher_name as class_teacher_name',
                    'sections.section_status',
                    'academic_students.academic_year',
                    DB::raw('COUNT(academic_students.id) as current_students')
                )
                ->leftJoin('edu_classes', 'sections.class_id', '=', 'edu_classes.id')
                ->leftJoin('edu_versions', 'sections.version_id', '=', 'edu_versions.id')
                ->leftJoin('teachers', 'sections.class_teacher_id', '=', 'teachers.id')
                ->leftJoin('academic_students', 'sections.id', '=', 'academic_students.section_id')
                ->where('sections.version_id', $versionId)
                ->where('academic_students.academic_year', $academicYear)
                ->groupBy(
                    'sections.id',
                    'sections.section_name',
                    'edu_classes.class_name',
                    'sections.version_id',
                    'edu_versions.version_name',
                    'sections.max_students',
                    'sections.class_teacher_id',
                    'teachers.teacher_name',
                    'sections.section_status',
                    'academic_students.academic_year'
                )
                ->get();


            // dd($sectionSummary);
            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_summery', compact('versions'));
    }
}
