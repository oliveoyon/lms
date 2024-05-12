<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
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
    function logout()
    {
        //Auth::logout(); it will also work, or we can specify like bellow line as guard name
        Auth::guard('std')->logout();
        return redirect('/');
    }
}
