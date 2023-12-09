<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function teacherlist()
    {
        $send['teachers'] = Teacher::get();
        return view('dashboard.admin.teacher.teacherlist', $send);
    }

    public function addTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_name' => 'required|string|max:255',
            'teacher_user_name' => 'required|string|max:50|unique:teachers,teacher_user_name',
            'teacher_mobile' => 'required|string|max:20',
            'teacher_email' => 'nullable|email|max:100',
            'teacher_designation' => 'required|string|max:100',
            'teacher_gender' => 'required|string|max:10',
            'teacher_password' => 'required|string|max:300',
            'teacher_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $teacher = new Teacher();
            $teacher->teacher_hash_id = md5(uniqid(rand(), true));
            $teacher->teacher_name = $request->input('teacher_name');
            $teacher->teacher_user_name = $request->input('teacher_user_name');
            $teacher->teacher_mobile = $request->input('teacher_mobile');
            $teacher->teacher_email = $request->input('teacher_email');
            $teacher->teacher_designation = $request->input('teacher_designation');
            $teacher->teacher_gender = $request->input('teacher_gender');
            $teacher->teacher_password = $request->input('teacher_password');
            $teacher->teacher_status = $request->input('teacher_status');
            $teacher->school_id = 1;
            $query = $teacher->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.teacher_add_msg'), 'redirect' => 'admin/teacher-list']);
            }
        }
    }

    public function getTeacherDetails(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $teacherDetails = Teacher::find($teacher_id);
        return response()->json(['details' => $teacherDetails]);
    }

    public function updateTeacherDetails(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $teacher = Teacher::find($teacher_id);

        $validator = Validator::make($request->all(), [
            'teacher_name' => 'required|string|max:255',
            'teacher_user_name' => 'required|string|max:50|unique:teachers,teacher_user_name,' . $teacher_id,
            'teacher_mobile' => 'required|string|max:20',
            'teacher_email' => 'nullable|email|max:100',
            'teacher_designation' => 'required|string|max:100',
            'teacher_gender' => 'required|string|max:10',
            'teacher_password' => 'required|string|max:300',
            'teacher_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $teacher->teacher_name = $request->input('teacher_name');
            $teacher->teacher_user_name = $request->input('teacher_user_name');
            $teacher->teacher_mobile = $request->input('teacher_mobile');
            $teacher->teacher_email = $request->input('teacher_email');
            $teacher->teacher_designation = $request->input('teacher_designation');
            $teacher->teacher_gender = $request->input('teacher_gender');
            $teacher->teacher_password = $request->input('teacher_password');
            $teacher->teacher_status = $request->input('teacher_status');
            $query = $teacher->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.teacher_edit_msg'), 'redirect' => 'admin/teacher-list']);
            }
        }
    }

    public function deleteTeacher(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $query = Teacher::find($teacher_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.teacher_del_msg'), 'redirect' => 'admin/teacher-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
    
    
}
