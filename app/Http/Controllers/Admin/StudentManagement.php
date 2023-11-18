<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentManagement extends Controller
{
    public function admission()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.StudentManagement.student_admission', compact( 'versions'));
    }

    public function stdAdmission1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'std_id' => 'required|string|max:15|unique:students,std_id',
            'std_name' => 'required|string|max:100',
            'std_name_bn' => 'nullable|string|max:200',
            'academic_year' => 'required|string|size:4',
            'version_id' => 'required|exists:edu_versions,id',
            'class_id' => 'required|exists:edu_classes,id',
            'section_id' => 'required|exists:sections,id',
            'admission_date' => 'nullable|date',
            'std_phone' => 'required|string|max:15',
            'std_phone1' => 'nullable|string|max:15',
            'std_fname' => 'required|string|max:100',
            'std_mname' => 'required|string|max:100',
            'std_dob' => 'nullable|date',
            'std_gender' => 'required|string|in:male,female,other',
            'std_email' => 'nullable|email|max:100',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'std_present_address' => 'required|string|max:200',
            'std_permanent_address' => 'required|string|max:200',
            'std_f_occupation' => 'nullable|string|max:30',
            'std_m_occupation' => 'nullable|string|max:30',
            'f_yearly_income' => 'nullable|numeric',
            'std_gurdian_name' => 'nullable|string|max:100',
            'std_gurdian_relation' => 'nullable|string|max:30',
            'std_gurdian_mobile' => 'nullable|string|max:15',
            'std_gurdian_address' => 'nullable|string|max:200',
            'std_picture' => 'nullable|string|max:15',
            'std_category' => 'required|string|max:15',
            'std_status' => 'required|in:0,1',
            'school_id' => 'required|exists:schools,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // $section = new Section();
        // $section->section_hash_id = md5(uniqid(rand(), true));
        // $section->section_name = $request->input('section_name');
        // $section->version_id = $request->input('version_id');
        // $section->class_id = $request->input('class_id');
        // $section->max_students = $request->input('max_students');
        // $section->class_teacher_id = $request->input('class_teacher_id');
        // $section->section_status = $request->input('section_status');
        // $query = $section->save();

        if ($query=NULL) {
            return response()->json(['code' => 1, 'msg' => __('language.section_add_msg') , 'redirect' => 'admin/section-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function stdAdmission(Request $request)
    {
        // Wrap the whole process in a database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'std_id' => 'required|string|max:15|unique:students,std_id',
                'std_name' => 'required|string|max:100',
                'std_name_bn' => 'nullable|string|max:200',
                'academic_year' => 'required|string|size:4',
                'version_id' => 'required|exists:edu_versions,id',
                'class_id' => 'required|exists:edu_classes,id',
                'section_id' => 'required|exists:sections,id',
                'admission_date' => 'nullable|date',
                'std_phone' => 'required|string|max:15',
                'std_phone1' => 'nullable|string|max:15',
                'std_fname' => 'required|string|max:100',
                'std_mname' => 'required|string|max:100',
                'std_dob' => 'nullable|date',
                'std_gender' => 'required|string|in:male,female,other',
                'std_email' => 'nullable|email|max:100',
                'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'std_present_address' => 'required|string|max:200',
                'std_permanent_address' => 'required|string|max:200',
                'std_f_occupation' => 'nullable|string|max:30',
                'std_m_occupation' => 'nullable|string|max:30',
                'f_yearly_income' => 'nullable|numeric',
                'std_gurdian_name' => 'nullable|string|max:100',
                'std_gurdian_relation' => 'nullable|string|max:30',
                'std_gurdian_mobile' => 'nullable|string|max:15',
                'std_gurdian_address' => 'nullable|string|max:200',
                'std_picture' => 'nullable|string|max:15',
                'std_category' => 'required|string|max:15',
                'std_status' => 'required|in:0,1',
                'school_id' => 'required|exists:schools,id',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // // Insert into students_academic table
            // $studentsAcademic = new StudentsAcademic();
            // // Set the fields for students_academic table
            // $studentsAcademic->std_hash_id = md5(uniqid(rand(), true));
            // // ... (Set other fields as needed)
            // $studentsAcademic->save();

            // // Insert into students table
            // $students = new Students();
            // // Set the fields for students table
            // $students->std_hash_id = md5(uniqid(rand(), true));
            // // ... (Set other fields as needed)
            // $students->save();

           

            // Commit the database transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.section_add_msg'), 'redirect' => 'admin/section-list']);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            return response()->json(['code' => 0, 'msg' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }
}
