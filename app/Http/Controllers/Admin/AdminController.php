<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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
            

        return view('dashboard.admin.home', $send);
    }



    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:4|max:8'
        ], [
            'email.exists' => 'This email is not in db'
        ]);

        $creds = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($creds)) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('admin.login')->with('fail', 'Credential fails');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'cur_pass' => 'required|min:4|max:8',
            'new_pass' => 'required|min:4|max:8',
            'cnew_pass' => 'required|min:4|max:8|same:new_pass',
        ], [
            'cnew_pass.same' => 'Hello',
        ]);


        $data = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        if (\Hash::check($request->cur_pass, $data->password)) {
            $user = Admin::find($data->id);
            $user->password = \Hash::make($request->new_pass);
            $user->pin = '';
            $user->verify = 1;
            $user->update();
            return redirect()->back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে');
        } else {
            return redirect()->back()->with('fail', 'Fails');
        }
    }

    function logout()
    {
        //Auth::logout(); it will also work, or we can specify like bellow line as guard name
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
