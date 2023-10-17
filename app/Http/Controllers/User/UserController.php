<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|max:8',
            'cpassword' => 'required|min:4|max:8|same:password',
        ]);

        $user = new User();
        $user->user_hash_id =  md5(uniqid(rand(), true));
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $save = $user->save();

        if($save){
            return redirect()->back()->with('success', 'User has been added successfully');
        }else{
            return redirect()->back()->with('fail', 'Something Wrong');
        }
    }

    public function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:4|max:8'
        ],[
            'email.exists' => 'Emain is not in db (custom msg)'
        ]);

        $creds = $request->only('email', 'password');
        if(Auth::guard('web')->attempt($creds)){
            return redirect()->route('user.home');
        }else{
            return redirect()->route('user.login')->with('fail', 'Something went wrong');
        }

    }

    public function changePassword(Request $request){
        $request->validate([
            'cur_pass' => 'required|min:4|max:8',
            'new_pass' => 'required|min:4|max:8',
            'cnew_pass' => 'required|min:4|max:8|same:new_pass',
        ],[
            'cnew_pass.same' => 'Hello',
        ]);

       
        $data = User::where('email', Auth::guard('web')->user()->email)->first();
        if (\Hash::check($request->cur_pass, $data->password)) {
            $user = User::find($data->id);
            $user->password = \Hash::make($request->new_pass);
            $user->pin = '';
            $user->verify = 1;
            $user->update();
            return redirect()->back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে');
        }else{
            return redirect()->back()->with('fail', 'Fails');
        }
    }

    function logout(){
        //Auth::logout(); it will also work, or we can specify like bellow line as guard name
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
