<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AssignTeacher;
use App\Models\Admin\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            $teacher->school_id = auth()->user()->school_id;;
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

    public function assignTeacherList()
    {
        $assignedTeachers = AssignTeacher::with('teacher', 'version', 'eduClass', 'subject', 'section')->get();

        // Assuming you have versions, classes, and subjects tables
        $versions = DB::table('edu_versions')->where('version_status', 1)->get();
        $classes = DB::table('edu_classes')->where('class_status', 1)->get();
        $sections = DB::table('sections')->where('section_status', 1)->get();
        $subjects = DB::table('subjects')->where('subject_status', 1)->get();
        $teachers = DB::table('teachers')->where('teacher_status', 1)->get();

        return view('dashboard.admin.teacher.assign_teacher', compact('assignedTeachers', 'versions', 'teachers', 'classes', 'sections', 'subjects'));
    }

    public function addAssignedTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|integer',
            'version_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'status' => 'required|in:0,1',
            
        ]);
        

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $existingRecord = AssignTeacher::where([
            'academic_year' => $request->input('academic_year'),
            'teacher_id' => $request->input('teacher_id'),
            'version_id' => $request->input('version_id'),
            'class_id' => $request->input('class_id'),
            'subject_id' => $request->input('subject_id'),
            'status' => $request->input('status'),
        ])->first();

        if ($existingRecord) {
            return response()->json(['code' => 0, 'error' => ['unique_combination' => 'The combination of academic year, teacher, version, class, and subject is not unique.']]);
        }

        $assignedTeacher = new AssignTeacher();
        $assignedTeacher->assign_hash_id = md5(uniqid(rand(), true));
        $assignedTeacher->academic_year = $request->input('academic_year');
        $assignedTeacher->version_id = $request->input('version_id');
        $assignedTeacher->class_id = $request->input('class_id');
        $assignedTeacher->section_id = $request->input('section_id');
        $assignedTeacher->subject_id = $request->input('subject_id');
        $assignedTeacher->teacher_id = $request->input('teacher_id');
        $assignedTeacher->school_id = auth()->user()->school_id;
        $assignedTeacher->status = $request->input('status');
        
        $query = $assignedTeacher->save();

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => __('language.assigned_teacher_add_msg'), 'redirect' => 'admin/assigned-teacher-list']);
        }
    }

    public function getAssignedTeacherDetails(Request $request)
    {
        $assigned_teacher_id = $request->assigned_teacher_id;
        $assignedTeacherDetails = AssignTeacher::find($assigned_teacher_id);

        // You may also eager load related data if needed
        // $assignedTeacherDetails = AssignTeacher::with('teacher', 'version', 'eduClass', 'subject')->find($assigned_teacher_id);

        return response()->json(['details' => $assignedTeacherDetails]);
    }

    public function updateAssignedTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|integer',
            'version_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $existingRecord = AssignTeacher::where([
            'academic_year' => $request->input('academic_year'),
            'teacher_id' => $request->input('teacher_id'),
            'version_id' => $request->input('version_id'),
            'class_id' => $request->input('class_id'),
            'subject_id' => $request->input('subject_id'),
            'status' => $request->input('status'),
        ])->where('id', '!=', $request->input('assigned_teacher_id'))->first();

        if ($existingRecord) {
            return response()->json(['code' => 0, 'error' => ['unique_combination' => 'The combination of academic year, teacher, version, class, and subject is not unique.']]);
        }

        $assignedTeacher = AssignTeacher::find($request->input('assigned_teacher_id'));
        $assignedTeacher->academic_year = $request->input('academic_year');
        $assignedTeacher->version_id = $request->input('version_id');
        $assignedTeacher->class_id = $request->input('class_id');
        $assignedTeacher->section_id = $request->input('section_id');
        $assignedTeacher->subject_id = $request->input('subject_id');
        $assignedTeacher->teacher_id = $request->input('teacher_id');
        $assignedTeacher->school_id = auth()->user()->school_id;
        $assignedTeacher->status = $request->input('status');

        $query = $assignedTeacher->save();

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => __('language.assigned_teacher_update_msg'), 'redirect' => 'admin/assigned-teacher-list']);
        }
    }

    public function deleteAssignedTeacher(Request $request)
    {
        $assigned_teacher_id = $request->assigned_teacher_id;

        $assignedTeacher = AssignTeacher::find($assigned_teacher_id);
        
        if (!$assignedTeacher) {
            return response()->json(['code' => 0, 'msg' => 'Record not found']);
        }

        $query = $assignedTeacher->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.assigned_teacher_del_msg'), 'redirect' => 'admin/assigned-teacher-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }



    
    
}
