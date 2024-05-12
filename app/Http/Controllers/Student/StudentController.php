<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Admin\DependentController;
use App\Http\Controllers\Controller;
use App\Models\Admin\Attendances;
use App\Models\Admin\Event;
use App\Models\Admin\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class StudentController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'std_id' => 'required|exists:academic_students,std_id',
            'password' => 'required|min:4|max:8'
        ], [
            'std_id.exists' => 'This Student Id is not in db'
        ]);

        $creds = $request->only('std_id', 'password');
        if (Auth::guard('std')->attempt($creds)) {
            return redirect()->route('student.home');
        } else {
            return redirect()->route('student.login')->with('fail', 'Credential fails');
        }
    }

    public function index()
    {
        $send['data'] = DB::select("
            SELECT
                (SELECT COUNT(*) FROM academic_students WHERE st_aca_status = 1) AS total_students,
                (SELECT COUNT(*) FROM teachers WHERE teacher_status = 1) AS total_teachers,
                (SELECT COUNT(*) FROM books WHERE book_status = 1) AS total_books,
                (SELECT COUNT(*) FROM tr_assign_stds WHERE tr_assign_status = 1) AS total_assigned_students")[0];

        $events = Event::where('event_status', 1)
        ->whereDate('start_date', '>=', now())
        ->select('event_title', 'start_date', 'end_date', 'url', 'color')
        ->get()
        ->map(function ($event) {
            return [
                'title' => $event->event_title,
                'start' => Carbon::parse($event->start_date)->toDateTimeString(),
                'end' => Carbon::parse($event->end_date)->toDateTimeString(),
                'url' => $event->url,
                'color' => $event->color,
            ];
        });

        $send['eventsJson'] = $events->toJson();


        return view('student.home', $send);
    }

    public function my_profile()
    {
        $whr = [
            'students.std_id' => auth()->user()->std_id,
        ];

        $student = DB::table('academic_students')
            ->where($whr)
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->join('edu_versions', 'academic_students.version_id', '=', 'edu_versions.id')
            ->join('edu_classes', 'academic_students.class_id', '=', 'edu_classes.id')
            ->join('sections', 'academic_students.section_id', '=', 'sections.id')
            ->select(
                'academic_students.id',
                'academic_students.academic_year',
                'academic_students.std_id',
                'students.*',

                'edu_versions.version_name',
                'edu_classes.class_name',
                'sections.section_name'
            )
            ->first();

        $attendanceData = Attendances::select(
            DB::raw('MONTH(attendance_date) as month'),
            DB::raw('SUM(CASE WHEN attendance = "Present" THEN 1 ELSE 0 END) as present_count'),
            DB::raw('SUM(CASE WHEN attendance = "Absent" THEN 1 ELSE 0 END) as absent_count'),
            DB::raw('SUM(CASE WHEN attendance = "Late" THEN 1 ELSE 0 END) as late_count'),
            DB::raw('COUNT(*) as total_days')
        )
            ->where('std_id', $student->std_id)
            ->groupBy(DB::raw('MONTH(attendance_date)'))
            ->orderBy(DB::raw('MONTH(attendance_date)'))
            ->get();

        // Calculate the overall totals
        $totalPresent = $attendanceData->sum('present_count');
        $totalAbsent = $attendanceData->sum('absent_count');
        $totalLate = $attendanceData->sum('late_count');

        $dependantController = new DependentController();
        $totalDues = $dependantController->getTotalDues($student->std_id);
        $detailedDues = $dependantController->getDetailedDues($student->std_id);

        // dd($student);
        return view('student.student_profile', compact('student', 'attendanceData', 'totalPresent', 'totalAbsent', 'totalLate', 'totalDues', 'detailedDues'));
    }

    public function mySubject()
    {
        $subjects = Subject::with(['version', 'class'])->get();
        // dd($subjects);

        return view('student.subject-list', compact('subjects'));
    }

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


    function logout()
    {
        //Auth::logout(); it will also work, or we can specify like bellow line as guard name
        Auth::guard('std')->logout();
        return redirect('/');
    }
}
