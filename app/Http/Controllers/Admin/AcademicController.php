<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use App\Models\Admin\Section;
use App\Models\Admin\Subject;
use App\Models\Admin\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicController extends Controller
{
    public function versionlist()
    {
        $send['versions'] = EduVersions::get();
        return view('dashboard.admin.academic.version', $send);
    }
    
    public function addVersion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version_name' => 'required|string|max:255',
            'version_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $version = new EduVersions();
            $version->version_hash_id = md5(uniqid(rand(), true));
            $version->version_name = $request->input('version_name');
            $version->version_status = $request->input('version_status');
            $query = $version->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.version_add_msg') , 'redirect'=> 'admin/version-list']);
            }
        }
    }

    public function getVersionDetails(Request $request)
    {
        $version_id = $request->version_id;
        $versionDetails = EduVersions::find($version_id);
        return response()->json(['details' => $versionDetails]);
    }

    //UPDATE Category DETAILS
    public function updateVersionDetails(Request $request)
    {
        $version_id = $request->vid;
        $version = EduVersions::find($version_id);

        $validator = Validator::make($request->all(), [
            'version_name' => 'required|string|max:255|unique:edu_versions,version_name,' . $version_id,
            'version_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $version->version_name = $request->input('version_name');
            $version->version_status = $request->input('version_status');
            $query = $version->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.version_edit_msg') , 'redirect'=> 'admin/version-list']);
            }
        }
    }

    public function deleteVersion(Request $request)
    {
        $version_id = $request->version_id;
        $query = EduVersions::find($version_id);
        $query = $query->delete();

        
        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.version_del_msg') , 'redirect' => 'admin/version-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function classlist()
    {
        $send['classes'] = EduClasses::get();
        $send['versions'] = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.academic.class', $send);
    }

    public function addClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:50|unique:edu_classes,class_name,NULL,id,version_id,' . $request->input('version_id'),
            'class_numeric' => 'required|integer|unique:edu_classes,class_name,NULL,id,version_id,' . $request->input('version_id'),
            'class_status' => 'required',
            'version_id' => 'required|exists:edu_versions,id', // Make sure the version exists
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $class = new EduClasses();
        $class->class_hash_id = md5(uniqid(rand(), true));
        $class->class_name = $request->input('class_name');
        $class->class_numeric = $request->input('class_numeric');
        $class->class_status = $request->input('class_status');
        $class->version_id = $request->input('version_id'); // Assign the version_id
        $query = $class->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.class_add_msg'), 'redirect' => 'admin/class-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function getClassDetails(Request $request)
    {
        $class_id = $request->class_id;
        $classDetails = EduClasses::find($class_id);
        return response()->json(['details' => $classDetails]);
    }

    public function updateClassDetails(Request $request)
    {
        // dd($request);
        $class_id = $request->cid;
        $class = EduClasses::find($class_id);

        

        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255|unique:edu_classes,class_name,' . $class_id,
            'class_numeric' => 'required|integer',
            'class_status' => 'required',
            'version_id' => 'required|exists:edu_versions,id', // Make sure the version exists
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $class->class_name = $request->input('class_name');
        $class->class_numeric = $request->input('class_numeric');
        $class->class_status = $request->input('class_status');
        $class->version_id = $request->input('version_id'); // Update the version_id
        $query = $class->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.class_edit_msg'), 'redirect' => 'admin/class-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteClass(Request $request)
    {
        $class_id = $request->class_id;
        $query = EduClasses::find($class_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.class_del_msg'), 'redirect' => 'admin/class-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    

    public function sectionList()
    {
        $sections = Section::with(['eduClass', 'version'])->get();
        $classes = EduClasses::get()->where('class_status', 1);
        $versions = EduVersions::get()->where('version_status', 1);
        $teachers = Teacher::get()->where('teacher_status', 1);

        return view('dashboard.admin.academic.section', compact('sections', 'classes', 'versions', 'teachers'));
    }


    public function addSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:100|unique:sections,section_name,NULL,id,class_id,' . $request->input('class_id'),
            'version_id' => 'required|exists:edu_versions,id',
            'class_id' => 'required|exists:edu_classes,id',
            'max_students' => 'required',
            // 'class_teacher_id' => 'required|exists:teachers,id',
            'section_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $section = new Section();
        $section->section_hash_id = md5(uniqid(rand(), true));
        $section->section_name = $request->input('section_name');
        $section->version_id = $request->input('version_id');
        $section->class_id = $request->input('class_id');
        $section->max_students = $request->input('max_students');
        $section->class_teacher_id = $request->input('class_teacher_id');
        $section->section_status = $request->input('section_status');
        $query = $section->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.section_add_msg') , 'redirect' => 'admin/section-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function getSectionDetails(Request $request)
    {
        $section_id = $request->section_id;
        $sectionDetails = Section::find($section_id);
        // dd($sectionDetails);
        return response()->json(['details' => $sectionDetails]);
    }

    public function updateSectionDetails(Request $request)
    {
        $section_id = $request->sid;
        $section = Section::find($section_id);
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:100|unique:sections,section_name,' . $section_id . ',id,class_id,' . ($section ? $section->class_id : null),
            'version_id' => 'required|exists:edu_versions,id',
            'max_students' => 'required',
            'section_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $section->section_name = $request->input('section_name');
        $section->version_id = $request->input('version_id');
        $section->class_teacher_id = $request->input('class_teacher_id');
        $section->class_id = $request->input('class_id');
        $section->max_students = $request->input('max_students');
        $section->section_status = $request->input('section_status');
       
        $query = $section->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.section_edit_msg') , 'redirect' => 'admin/section-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteSection(Request $request)
    {
        $section_id = $request->section_id;
        $query = Section::find($section_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.section_del_msg') , 'redirect' => 'admin/section-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function subjectList()
    {
        // Retrieve a list of subjects and any related data you need
        $subjects = Subject::with(['version', 'class'])->get();
        $versions = EduVersions::get()->where('version_status', 1);
        $classes = EduClasses::get()->where('class_status', 1);;

        return view('dashboard.admin.academic.subject', compact('subjects', 'versions', 'classes'));
    }

    public function addSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_name' => 'required|string|max:200|unique:subjects,subject_name,NULL,id,class_id,' . $request->input('class_id'),
            'subject_code' => 'required|string|max:10',
            'academic_year' => 'required|digits:4',
            'subject_status' => 'required',
            'version_id' => 'required|exists:edu_versions,id',
            'class_id' => 'required|exists:edu_classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $subject = new Subject();
        $subject->subject_hash_id = md5(uniqid(rand(), true));
        $subject->subject_name = $request->input('subject_name');
        $subject->subject_code = $request->input('subject_code');
        $subject->academic_year = $request->input('academic_year');
        $subject->subject_status = $request->input('subject_status');
        $subject->version_id = $request->input('version_id');
        $subject->class_id = $request->input('class_id');

        $query = $subject->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.subject_add_msg') , 'redirect' => 'admin/subject-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }



    public function getSubjectDetails(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $subjectDetails = Subject::find($subjectId);

        if ($subjectDetails) {
            return response()->json(['details' => $subjectDetails]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Subject not found']);
        }
    }

    public function updateSubjectDetails(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $subject = Subject::find($subjectId);

        if (!$subject) {
            return response()->json(['code' => 0, 'msg' => 'Subject not found']);
        }

        $validator = Validator::make($request->all(), [
            'subject_name' => 'required|string|max:100',
            'subject_code' => 'required|string|max:10',
            'academic_year' => 'required|string|max:4',
            'class_id' => 'required|exists:edu_classes,id',
            'subject_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $subject->subject_name = $request->input('subject_name');
        $subject->subject_code = $request->input('subject_code');
        $subject->academic_year = $request->input('academic_year');
        $subject->class_id = $request->input('class_id');
        $subject->subject_status = $request->input('subject_status');

        if ($subject->save()) {
            return response()->json(['code' => 1, 'msg' => __('language.subject_edit_msg') , 'redirect' => 'admin/subject-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteSubject(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $subject = Subject::find($subjectId);

        if ($subject) {
            if ($subject->delete()) {
                return response()->json(['code' => 1, 'msg' => __('language.subject_del_msg') , 'redirect' => 'admin/subject-list']);
            }
        }

        return response()->json(['code' => 0, 'msg' => 'Subject not found or could not be deleted']);
    }


}
