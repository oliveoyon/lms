<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\Attendances;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use App\Models\Admin\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function attendanceInput()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.attendance.attendance_input', compact( 'versions'));
    }

    public function fetchStudents(Request $request)
    {
        $academicYear = $request->input('academic_year');
        $versionId = $request->input('version_id');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');

        try {
            // Fetch academic students with associated student information
            $academicStudents = AcademicStudent::with('student')
                ->where([
                    'academic_year' => $academicYear,
                    'version_id' => $versionId,
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                ])->get();

            // Fetch class and section information
            $classData = EduClasses::find($classId);
            $sectionData = Section::find($sectionId);

            // Transform the data to include student name
            $transformedData = $academicStudents->map(function ($academicStudent) {
                $student = $academicStudent->student;
                return [
                    'id' => $academicStudent->id,
                    'std_id' => $academicStudent->std_id,
                    'std_name' => $student ? $student->std_name : null,
                    // Add other fields as needed
                ];
            });

            $currentDate = now()->toDateString();

            return response()->json([
                'students' => $transformedData,
                'currentDate' => $currentDate,
                'classData' => $classData,
                'sectionData' => $sectionData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching students.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function addAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|integer',
            'version_id' => 'required|exists:edu_versions,id',
            'class_id' => 'required|exists:edu_classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Check if attendance for the given date already exists
        $existingAttendance = Attendances::where([
            'academic_year' => $request->academic_year,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'attendance_date' => $request->attendance_date,
        ])->exists();

        if ($existingAttendance) {
            return response()->json(['code' => 0, 'msg' => 'Attendance for this date already exists']);
        }

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Loop through action_* keys and insert attendance records
            foreach ($request->except(['_token', 'academic_year', 'version_id', 'class_id', 'section_id', 'attendance_date']) as $key => $value) {
                // Extract student id from the key (action_*)
                $studentId = substr($key, strlen('action_'));

                // Save attendance data for each student
                Attendances::create([
                    'attendance_hash_id' => md5(uniqid(rand(), true)), // You need to implement a function to generate a unique hash
                    'std_id' => $studentId,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'roll_no' => 1, // You need to implement a function to get roll number based on student id
                    'academic_year' => $request->academic_year,
                    'attendance' => $value,
                    'attendance_date' => $request->attendance_date,
                    'month' => date('F', strtotime($request->attendance_date)), // Get the month from the date
                    'school_id' => auth()->user()->school_id, // You need to implement a function to get school id based on class id
                ]);
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.periods_add_msg'), 'redirect' => 'admin/attendance-input']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollback();



            return response()->json(['code' => 0, 'error' => ['general' => 'An error occurred while saving attendance']]);
        }
    }

    public function attendanceEdit()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.attendance.edit_attendance', compact( 'versions'));
    }

    public function fetchAttendanceData(Request $request)
{
    $academicYear = $request->input('academic_year');
    $versionId = $request->input('version_id');
    $classId = $request->input('class_id');
    $sectionId = $request->input('section_id');
    $attendanceDate = $request->input('attendance_date');

    try {
        // Fetch attendance data based on the selected values
        $attendanceData = DB::table('attendances')
        ->select('attendances.*', 'students.std_name')
        ->join('students', 'attendances.std_id', '=', 'students.std_id')
        ->where([
            'attendances.academic_year' => $academicYear,
            'attendances.class_id' => $classId,
            'attendances.class_id' => $classId,
            'attendances.section_id' => $sectionId,
            'attendances.attendance_date' => $attendanceDate,
        ])
        ->get();

        // Fetch class and section information
        $classData = EduClasses::find($classId);
        $sectionData = Section::find($sectionId);



        // Transform the data as needed
        $transformedData = $attendanceData->map(function ($attendanceRecord) {
            // Transform the data structure based on your requirements

            return [
                'id' => $attendanceRecord->id,
                'std_id' => $attendanceRecord->std_id,
                'std_name' => $attendanceRecord->std_name,

                'attendance' => $attendanceRecord->attendance,
                // Add other fields as needed
            ];
        });

        $currentDate = now()->toDateString();

        return response()->json([
            'students' => $transformedData,
            'currentDate' => $currentDate,
            'classData' => $classData,
            'sectionData' => $sectionData,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred while fetching attendance data.',
            'message' => $e->getMessage(),
        ], 500);
    }
}

public function updateAttendance(Request $request)
{
    $validator = Validator::make($request->all(), [
        'academic_year' => 'required|integer',
        'version_id' => 'required|exists:edu_versions,id',
        'class_id' => 'required|exists:edu_classes,id',
        'section_id' => 'required|exists:sections,id',
        'attendance_date' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
    }

    // Begin a database transaction
    DB::beginTransaction();

    try {
        // Loop through action_* keys and update attendance records
        foreach ($request->except(['_token', 'academic_year', 'version_id', 'class_id', 'section_id', 'attendance_date']) as $key => $value) {
            // Extract student id from the key (action_*)
            $studentId = substr($key, strlen('action_'));

            // Update attendance data for each student
            Attendances::where([
                'academic_year' => $request->academic_year,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'attendance_date' => $request->attendance_date,
                'std_id' => $studentId,
            ])->update([
                'attendance' => $value,
            ]);
        }

        // Commit the transaction
        DB::commit();

        return response()->json(['code' => 1, 'msg' => __('language.periods_update_msg'), 'redirect' => 'admin/edit-attendance']);
    } catch (\Exception $e) {
        // Rollback the transaction in case of an exception
        DB::rollback();

        return response()->json(['code' => 0, 'error' => ['general' => 'An error occurred while updating attendance']]);
    }
}





}
