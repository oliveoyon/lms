<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'name' => 'required|exists:admins,name',
            'password' => 'required|min:4|max:8'
        ], [
            'name.exists' => 'This email is not in db'
        ]);

        $creds = $request->only('name', 'password');
        if (Auth::guard('std')->attempt($creds)) {
            return redirect()->route('admin.home');
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
