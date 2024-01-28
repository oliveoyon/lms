<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeAmount;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\AcademicFeeHead;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\Attendances;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use App\Models\Admin\FeeFrequency;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
            'margin_top' => 35,
            'margin_bottom' => 5,
            'margin_header' => 5,
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

    public function class_statistics(Request $request)
    {
        if ($request->isMethod('post')) {

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'academic_year' => 'required',
                'version_id' => 'required',
                'class_id' => 'required',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Retrieve validated data
            $academicYear = $request->input('academic_year');
            $versionId = $request->input('version_id');
            $classId = $request->input('class_id');

            // Attendance Statistics with Percentage
            $send['attendanceStats'] = DB::table('students')
                ->leftJoin('attendances', function ($join) use ($academicYear, $versionId, $classId) {
                    $join->on('students.std_id', '=', 'attendances.std_id')
                        ->where('attendances.academic_year', $academicYear)
                        // ->where('attendances.version_id', $versionId)
                        ->where('attendances.class_id', $classId);
                })
                ->select(
                    'students.std_gender',
                    'students.std_category',
                    DB::raw('count(*) as total_students'),
                    DB::raw('sum(case when attendances.attendance = "Present" then 1 else 0 end) as present_students'),
                    DB::raw('sum(case when attendances.attendance = "Absent" then 1 else 0 end) as absent_students'),
                    DB::raw('sum(case when attendances.attendance = "Late" then 1 else 0 end) as latecomers'),
                )
                ->groupBy('students.std_gender', 'students.std_category')
                ->get();

            // dd($attendanceStats);
            // Blood Group-wise Statistics
            $send['bloodGroupStats'] = DB::table('students')
                ->where('academic_year', $academicYear)
                ->where('version_id', $versionId)
                ->where('class_id', $classId)
                ->select(
                    DB::raw('CASE
                    WHEN blood_group = "A+" THEN "A+"
                    WHEN blood_group = "B+" THEN "B+"
                    WHEN blood_group = "AB+" THEN "AB+"
                    WHEN blood_group = "O+" THEN "O+"
                    WHEN blood_group = "A-" THEN "A-"
                    WHEN blood_group = "B-" THEN "B-"
                    WHEN blood_group = "AB-" THEN "AB-"
                    WHEN blood_group = "O-" THEN "O-"
                    ELSE "Unknown"
                END as blood_group_category'),
                    DB::raw('count(*) as count')
                )
                ->groupBy('blood_group_category')
                ->pluck('count', 'blood_group_category');




            return response()->json($send);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_statistics', compact('versions'));
    }

    public function class_student_count(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $versionId = request('version_id');
            $classId = request('class_id');

            // Build the query
            $classes = DB::table('students')
                ->select(DB::raw('students.academic_year, edu_versions.version_name as version, edu_classes.class_name as class, sections.section_name as section, count(students.id) as student_count'))
                ->join('edu_versions', 'students.version_id', '=', 'edu_versions.id')
                ->join('edu_classes', 'students.class_id', '=', 'edu_classes.id')
                ->join('sections', 'students.section_id', '=', 'sections.id')
                ->when($academicYear, function ($query) use ($academicYear) {
                    return $query->where('students.academic_year', $academicYear);
                })
                ->when($versionId, function ($query) use ($versionId) {
                    return $query->where('students.version_id', $versionId);
                })
                ->when($classId, function ($query) use ($classId) {
                    return $query->where('students.class_id', $classId);
                })
                ->groupBy('students.academic_year', 'edu_versions.version_name', 'edu_classes.class_name', 'sections.section_name')
                ->orderBy('students.academic_year', 'asc')
                ->orderBy('edu_versions.version_name', 'asc')
                ->orderBy('edu_classes.class_name', 'asc')
                ->orderBy('sections.section_name', 'asc')
                ->get();

            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_wise_student_count', compact('versions'));
    }

    public function subject_list(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $versionId = request('version_id');
            $classId = request('class_id');

            // Build the query
            $classes = DB::table('subjects')
                ->select(DB::raw('subjects.academic_year, edu_versions.version_name as version, edu_classes.class_name as class, subjects.subject_code, subjects.subject_name, subjects.subject_status'))
                ->join('edu_versions', 'subjects.version_id', '=', 'edu_versions.id')
                ->join('edu_classes', 'subjects.class_id', '=', 'edu_classes.id')
                ->when($academicYear, function ($query) use ($academicYear) {
                    return $query->where('subjects.academic_year', $academicYear);
                })
                ->when($versionId, function ($query) use ($versionId) {
                    return $query->where('subjects.version_id', $versionId);
                })
                ->when($classId, function ($query) use ($classId) {
                    return $query->where('subjects.class_id', $classId);
                })
                ->orderBy('subjects.academic_year', 'asc')
                ->orderBy('edu_versions.version_name', 'asc')
                ->orderBy('edu_classes.class_name', 'asc')
                ->orderBy('subjects.subject_code', 'asc')
                ->get();

            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_wise_subject_list', compact('versions'));
    }

    public function subject_count(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $versionId = request('version_id');
            $classId = request('class_id');

            // Build the query
            $classes = DB::table('subjects')
                ->select(DB::raw('subjects.academic_year, edu_versions.version_name as version, edu_classes.class_name as class, count(subjects.id) as subject_count'))
                ->join('edu_versions', 'subjects.version_id', '=', 'edu_versions.id')
                ->join('edu_classes', 'subjects.class_id', '=', 'edu_classes.id')
                ->when($academicYear, function ($query) use ($academicYear) {
                    return $query->where('subjects.academic_year', $academicYear);
                })
                ->when($versionId, function ($query) use ($versionId) {
                    return $query->where('subjects.version_id', $versionId);
                })
                ->when($classId, function ($query) use ($classId) {
                    return $query->where('subjects.class_id', $classId);
                })
                ->groupBy('subjects.academic_year', 'edu_versions.version_name', 'edu_classes.class_name')
                ->orderBy('subjects.academic_year', 'asc')
                ->orderBy('edu_versions.version_name', 'asc')
                ->orderBy('edu_classes.class_name', 'asc')
                ->get();

            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_wise_subject_count', compact('versions'));
    }

    public function section_wise_teacher(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $versionId = request('version_id');
            $classId = request('class_id');
            $sectionId = request('section_id');

            // Build the query
            $classes = DB::table('assign_teachers')
                ->select(
                    'assign_teachers.academic_year',
                    'edu_versions.version_name as version',
                    'edu_classes.class_name as class',
                    'sections.section_name as section',
                    'subjects.subject_name as subject',
                    'teachers.teacher_name as teacher',
                    'teachers.teacher_mobile as mobile',
                    'teachers.teacher_email as email',
                    'assign_teachers.status'
                )
                ->join('edu_versions', 'assign_teachers.version_id', '=', 'edu_versions.id')
                ->join('edu_classes', 'assign_teachers.class_id', '=', 'edu_classes.id')
                ->join('sections', 'assign_teachers.section_id', '=', 'sections.id')
                ->join('subjects', 'assign_teachers.subject_id', '=', 'subjects.id')
                ->join('teachers', 'assign_teachers.teacher_id', '=', 'teachers.id')
                ->when($academicYear, function ($query) use ($academicYear) {
                    return $query->where('assign_teachers.academic_year', $academicYear);
                })
                ->when($versionId, function ($query) use ($versionId) {
                    return $query->where('assign_teachers.version_id', $versionId);
                })
                ->when($classId, function ($query) use ($classId) {
                    return $query->where('assign_teachers.class_id', $classId);
                })
                ->when($sectionId, function ($query) use ($sectionId) {
                    return $query->where('assign_teachers.section_id', $sectionId);
                })
                ->orderBy('assign_teachers.academic_year', 'asc')
                ->orderBy('edu_versions.version_name', 'asc')
                ->orderBy('edu_classes.class_name', 'asc')
                ->orderBy('sections.section_name', 'asc')
                ->get();

            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.section_wise_teacher', compact('versions'));
    }

    public function guardian_list(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $versionId = request('version_id');
            $classId = request('class_id');
            $sectionId = request('section_id');

            // Build the query
            $classes = DB::table('students')
            ->select(
                'students.academic_year',
                'edu_versions.version_name as version',
                'edu_classes.class_name as class',
                'sections.section_name as section',
                'students.std_name as student_name',
                'students.std_fname as student_fname',
                'students.std_mname as student_mname',
                'students.std_phone as student_phone',
                'students.std_present_address as address',
                'students.std_gurdian_name as guardian_name',
                'students.std_gurdian_relation as guardian_relation',
                'students.std_gurdian_mobile as guardian_mobile',
                'students.std_gurdian_address as guardian_address'
            )
            ->join('edu_versions', 'students.version_id', '=', 'edu_versions.id')
            ->join('edu_classes', 'students.class_id', '=', 'edu_classes.id')
            ->leftJoin('sections', 'students.section_id', '=', 'sections.id') // Left join to include optional section_id
            ->when($academicYear, function ($query) use ($academicYear) {
                return $query->where('students.academic_year', $academicYear);
            })
            ->when($versionId, function ($query) use ($versionId) {
                return $query->where('students.version_id', $versionId);
            })
            ->when($classId, function ($query) use ($classId) {
                return $query->where('students.class_id', $classId);
            })
            ->when($sectionId, function ($query) use ($sectionId) {
                return $query->where('students.section_id', $sectionId);
            })
            ->orderBy('students.academic_year', 'asc')
            ->orderBy('edu_versions.version_name', 'asc')
            ->orderBy('edu_classes.class_name', 'asc')
            ->orderBy('sections.section_name', 'asc')
            ->orderBy('students.std_name', 'asc')
            ->get();
            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.guardian_list', compact('versions'));
    }

    public function class_attendance(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $versionId = request('version_id');
            $classId = request('class_id');
            $sectionId = request('section_id');
            $monthId = request('month_id');
            $monthId = 'January';

            // Build the query
            $classes = DB::table('attendances')
            ->select(
                'attendances.academic_year',
                'edu_classes.class_name as class',
                'sections.section_name as section',
                'attendances.attendance_date',
                'attendances.attendance'
            )
            ->join('edu_classes', 'attendances.class_id', '=', 'edu_classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id') // Left join to include optional section_id
            ->when($academicYear, function ($query) use ($academicYear) {
                return $query->where('attendances.academic_year', $academicYear);
            })
            ->when($classId, function ($query) use ($classId) {
                return $query->where('attendances.class_id', $classId);
            })
            ->when($sectionId, function ($query) use ($sectionId) {
                return $query->where('attendances.section_id', $sectionId);
            })
            ->when($monthId, function ($query) use ($monthId) {
                return $query->where('attendances.month', $monthId);
            })
            ->orderBy('attendances.academic_year', 'asc')
            ->orderBy('edu_classes.class_name', 'asc')
            ->orderBy('sections.section_name', 'asc')
            ->orderBy('attendances.attendance_date', 'asc')
            ->get();
            // dd($classes);
            return response()->json(['classes' => $classes]);
        }



        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.class_attendance', compact('versions'));
    }

    // Fee Setup Reports

    public function FrequencyList()
    {
        $send['feeFrequencies'] = FeeFrequency::get();
        return view('dashboard.admin.reports.frequencylist', $send);
    }

    public function FeeHeadList()
    {
        $send['academicFeeHeads'] = AcademicFeeHead::all();
        $send['feeFrequencies'] = FeeFrequency::get()->where('freq_status', 1);
        return view('dashboard.admin.reports.feehead', $send);
    }

    // public function FeeGroupList()
    // {
    //     $send['feeHeads'] = AcademicFeeHead::get()->where('status', 1);
    //     $academicFeeGroups = AcademicFeeGroup::get();

    //     $academicFeeGroups->each(function ($feeGroup) {
    //         $acaFeeheadIds = explode(',', $feeGroup->aca_feehead_id);
    //         $acaFeeheadNames = AcademicFeeHead::whereIn('id', $acaFeeheadIds)
    //             ->pluck('aca_feehead_name')
    //             ->implode(', ');
    //         $feeGroup->aca_feehead_id = $acaFeeheadNames;
    //     });
    //     $send['academicFeeGroups'] = $academicFeeGroups;
    //     return view('dashboard.admin.reports.feegroups', $send);
    // }

    public function FeeGroupList(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $whr = [
                'academic_year' => $academicYear,
            ];
            $whr = array_filter($whr);
            $academicFeeGroups = AcademicFeeGroup::where($whr)->get();
            $academicFeeGroups->each(function ($feeGroup) {
                $acaFeeheadIds = explode(',', $feeGroup->aca_feehead_id);
                $acaFeeheadNames = AcademicFeeHead::whereIn('id', $acaFeeheadIds)
                    ->pluck('aca_feehead_name')
                    ->implode(', ');
                $feeGroup->aca_feehead_id = $acaFeeheadNames;
            });
            $classes = $academicFeeGroups;


            // dd($classes);
            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.feegroup', compact('versions'));
    }

    public function dueFeeList(Request $request)
    {
        if ($request->isMethod('post')) {
            $academicYear = request('academic_year');
            $classId = request('class_id');
            $sectionId = request('section_id');
            $vserionId = request('version_id');
            $currentMonth = now()->format('m'); // Get the current month


            $whr = [
                'academic_students.academic_year' => $academicYear,
                'academic_students.class_id' => $classId,
                'academic_students.section_id' => $sectionId,
                'academic_students.version_id' => $vserionId,
            ];
            $whr = array_filter($whr);



            $classes = DB::table('academic_students')
            ->leftJoin('fee_collections', 'academic_students.std_id', '=', 'fee_collections.std_id')
            ->leftJoin('fee_payments', 'fee_collections.id', '=', 'fee_payments.fee_collection_id')
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->join('edu_classes', 'academic_students.class_id', '=', 'edu_classes.id')
            ->join('sections', 'academic_students.section_id', '=', 'sections.id')
            ->join('edu_versions', 'academic_students.version_id', '=', 'edu_versions.id')
            ->where($whr)

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


            // dd($classes);
            return response()->json(['classes' => $classes]);
        }

        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.reports.dueList', compact('versions'));
    }

    public function FeeAmountList()
    {
        $academicFeeAmounts = AcademicFeeAmount::orderBy('class_id', 'asc')->with('academicFeeGroup', 'academicFeeHead', 'eduClass')
        // ->Where('academic_year', 2024)
        ->get();
        $feeGroups = AcademicFeeGroup::all();
        $classes = EduClasses::get()->where('class_status', 1);
        return view('dashboard.admin.reports.feeamount', compact('academicFeeAmounts', 'feeGroups', 'classes'));
    }

}
