<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeAmount;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\AcademicFeeHead;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\AppliedStudent;
use App\Models\Admin\Attendances;
use App\Models\Admin\EduVersions;
use App\Models\Admin\FeeCollection;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class StudentManagement extends Controller
{
    public function getfeedet()
    {
        $std_id = 23001;
        $dependantController = new DependentController();
        $totalDues = $dependantController->getTotalDues($std_id);
        $detailedDues = $dependantController->getDetailedDues($std_id);
        // dd($totalDues);
        dd($detailedDues);
    }

    public function admission()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        $feegroups = AcademicFeeGroup::get()->where('aca_group_status', 1);
        return view('dashboard.admin.StudentManagement.student_admission', compact('versions', 'feegroups'));
    }

    public function stdAdmission(Request $request)
    {
        // Wrap the whole process in a database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                // 'std_id' => 'required|string|max:15|unique:students,std_id',
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
                'std_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'std_category' => 'required|string|max:15',
                // 'std_status' => 'required|in:0,1',
                // 'school_id' => 'required|exists:schools,id',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $lastTwoDigits = substr($request->input('academic_year'), -2);
            $std_id = $lastTwoDigits . str_pad(Student::max('id') + 1, 3, '0', STR_PAD_LEFT);

            if ($request->hasFile('std_picture')) {
                $path = 'img/std_img/';
                $file = $request->file('std_picture');
                $fileExtension = $file->getClientOriginalExtension(); // Get the file extension
                $file_name = $std_id . '.' . $fileExtension;
                $upload = $file->storeAs($path, $file_name, 'public');
            } else {
                $file_name = '';
            }

            $std_hash_id = md5(uniqid(rand(), true));
            $student = new Student([
                'std_hash_id' => $std_hash_id,
                'std_id' => $std_id,
                'std_name' => $request->input('std_name'),
                'std_name_bn' => $request->input('std_name_bn'),
                'academic_year' => $request->input('academic_year'),
                'version_id' => $request->input('version_id'),
                'class_id' => $request->input('class_id'),
                'section_id' => $request->input('section_id'),
                'admission_date' => $request->input('admission_date'),
                'std_phone' => $request->input('std_phone'),
                'std_phone1' => $request->input('std_phone1'),
                'std_fname' => $request->input('std_fname'),
                'std_mname' => $request->input('std_mname'),
                'std_dob' => $request->input('std_dob'),
                'std_gender' => $request->input('std_gender'),
                'std_email' => $request->input('std_email'),
                'blood_group' => $request->input('blood_group'),
                'std_present_address' => $request->input('std_present_address'),
                'std_permanent_address' => $request->input('std_permanent_address'),
                'std_f_occupation' => $request->input('std_f_occupation'),
                'std_m_occupation' => $request->input('std_m_occupation'),
                'f_yearly_income' => $request->input('f_yearly_income'),
                'std_gurdian_name' => $request->input('std_gurdian_name'),
                'std_gurdian_relation' => $request->input('std_gurdian_relation'),
                'std_gurdian_mobile' => $request->input('std_gurdian_mobile'),
                'std_gurdian_address' => $request->input('std_gurdian_address'),
                // 'std_picture' => $request->input('std_picture'),
                'std_picture' => $file_name,
                'std_category' => $request->input('std_category'),
                'std_status' => 1,
                'school_id' => auth()->user()->school_id,
            ]);
            // Save the student
            $student->save();

            $studentaca = new AcademicStudent([
                'std_hash_id' => $std_hash_id,
                'std_id' => $std_id,
                'academic_year' => $request->input('academic_year'),
                'version_id' => $request->input('version_id'),
                'class_id' => $request->input('class_id'),
                'section_id' => $request->input('section_id'),
                'std_password' => Hash::make($std_id),
                'roll_no' => 1,
                'st_aca_status' => 1,
                'school_id' => auth()->user()->school_id,
            ]);
            // Save the student
            $studentaca->save();

            $id = $request->input('feeSetup');
            $groups = AcademicFeeGroup::find($id);

            // Assuming $groups is not null
            if ($groups) {
                $feeIds = $groups->aca_feehead_id;
                $feeIdsArray = explode(",", $feeIds);

                foreach ($feeIdsArray as $feeId) {
                    // Fetch academic_fee_heads information
                    $feeHead = AcademicFeeHead::find($feeId);

                    if ($feeHead) {
                        // Divide no_of_installment by 12
                        $installments = $feeHead->no_of_installment;

                        // Fetch academic_fee_amounts information
                        $feeAmount = AcademicFeeAmount::where('aca_feehead_id', $feeId)
                            ->where('aca_group_id', $id) // Assuming you want to match aca_group_id
                            ->first();

                        // Now you can access the information and save into fee_collection table
                        if ($feeAmount) {
                            for ($i = 1; $i <= $installments; $i++) {
                                $dueDate = now()->addMonths(($i - 1) * 12 / $installments + 1)->startOfMonth()->addDays(19); // Calculate due date dynamically
                                $formattedDueDate = $dueDate->format('F'); // Get month name

                                // Add brackets and month name if installments is greater than one
                                $formattedDescription = $feeHead->aca_feehead_description;
                                if ($installments > 1) {
                                    $formattedDescription .= " ($formattedDueDate)";
                                }

                                // Save into fee_collection table
                                FeeCollection::create([
                                    'fee_collection_hash_id' => md5(uniqid(rand(), true)),
                                    'std_id' => $std_id,
                                    'academic_year' => $request->input('academic_year'),
                                    'fee_group_id' => $groups->id,
                                    'aca_feehead_id' => $feeId,
                                    'aca_feeamount_id' => $feeAmount->id,
                                    'payable_amount' => $feeAmount->amount,
                                    'is_paid' => false,
                                    'due_date' => $dueDate,
                                    'fee_description' => $formattedDescription,
                                    'fee_collection_status' => 1,
                                    'school_id' => auth()->user()->school_id,
                                ]);
                            }
                        }
                    }
                }
            }



            // Commit the database transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.std_add_msg'), 'redirect' => 'admin/student-admission']);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            return response()->json(['code' => 0, 'msg' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    public function bulkadmission()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        $feegroups = AcademicFeeGroup::get()->where('aca_group_status', 1);
        return view('dashboard.admin.StudentManagement.bulkstudent_admission', compact('versions', 'feegroups'));
    }

    public function bulkstdAdmission(Request $request)
    {
        // Wrap the whole process in a database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'academic_year' => 'required|string|size:4',
                'version_id' => 'required|exists:edu_versions,id',
                'class_id' => 'required|exists:edu_classes,id',
                'section_id' => 'required|exists:sections,id',
                'admission_date' => 'nullable|date',
                'std_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'std_category' => 'required|string|max:15',
                'feeSetup' => 'required',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            if ($request->hasFile('upload')) {
                // Create a CSV reader
                $csv = Reader::createFromPath($request->file('upload')->getPathname(), 'r');


                $csv->setHeaderOffset(0);
                // $csv->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
                // Get the records including the header
                $data = $csv->getRecords();

                foreach ($data as $index => $record) {
                    if ($index === 0) {
                        // Skip the header row
                        continue;
                    }

                    // Actual Works Start individual task
                    $lastTwoDigits = substr($request->input('academic_year'), -2);
                    $std_id = $lastTwoDigits . str_pad(Student::max('id') + 1, 3, '0', STR_PAD_LEFT);
                    $std_hash_id = md5(uniqid(rand(), true));
                    // Map headers to database fields
                    $mapping = [
                        'Student Name' => 'std_name',
                        'Student Name (Bengali)' => 'std_name_bn',
                        'Phone Number' => 'std_phone',
                        'Alternate Phone' => 'std_phone1',
                        'Father\'s Name' => 'std_fname',
                        'Mother\'s Name' => 'std_mname',
                        'Date of Birth' => 'std_dob',
                        'Gender' => 'std_gender',
                        'Email' => 'std_email',
                        'Blood Type' => 'blood_group',
                        'Present Address' => 'std_present_address',
                        'Permanent Address' => 'std_permanent_address', // Make sure this is correctly mapped
                        'Father\'s Occupation' => 'std_f_occupation',
                        'Mother\'s Occupation' => 'std_m_occupation',
                        'Yearly Income' => 'f_yearly_income',
                        'Guardian Name' => 'std_gurdian_name',
                        'Guardian Relation' => 'std_gurdian_relation',
                        'Guardian Phone' => 'std_gurdian_mobile',
                        'Guardian Address' => 'std_gurdian_address',
                    ];

                    $studentData = [
                        'std_hash_id' => $std_hash_id,
                        'std_id' => $std_id,
                        'academic_year' => $request->input('academic_year'),
                        'version_id' => $request->input('version_id'),
                        'class_id' => $request->input('class_id'),
                        'section_id' => $request->input('section_id'),
                        'admission_date' => $request->input('admission_date'),
                        'std_category' => $request->input('std_category'),
                        'std_status' => 1,
                        'school_id' => auth()->user()->school_id,
                    ];

                    foreach ($mapping as $header => $field) {
                        if ($field === 'std_dob') {
                            $studentData[$field] = Carbon::parse($record[$header])->format('Y-m-d');
                        } elseif ($field === 'std_permanent_address') {
                            $studentData[$field] = $record['Permanent Address'];
                        } else {
                            $studentData[$field] = $record[$header];
                        }
                    }

                    $student = new Student($studentData);

                    // Save the student
                    $student->save();

                    // Create Academic Student record (you may customize this part)
                    $studentaca = new AcademicStudent([
                        'std_hash_id' => $std_hash_id,
                        'std_id' => $std_id,
                        'academic_year' => $request->input('academic_year'),
                        'version_id' => $request->input('version_id'),
                        'class_id' => $request->input('class_id'),
                        'section_id' => $request->input('section_id'),
                        'std_password' => Hash::make($std_id),
                        'roll_no' => 1,
                        'st_aca_status' => 1,
                        'school_id' => auth()->user()->school_id,
                    ]);

                    // Save the academic student
                    $studentaca->save();

                    // Additional logic for fee collection (you may customize this part)
                    $id = $request->input('feeSetup');
                    $groups = AcademicFeeGroup::find($id);

                    if ($groups) {
                        $feeIds = $groups->aca_feehead_id;
                        $feeIdsArray = explode(",", $feeIds);

                        foreach ($feeIdsArray as $feeId) {
                            $feeHead = AcademicFeeHead::find($feeId);

                            if ($feeHead) {
                                $feeAmount = AcademicFeeAmount::where('aca_feehead_id', $feeId)
                                    ->where('aca_group_id', $id)
                                    ->first();

                                if ($feeAmount) {
                                    $installments = $feeHead->no_of_installment;

                                    for ($i = 1; $i <= $installments; $i++) {
                                        // Calculate due date with an adjustment to start from January
                                        $dueDate = now()->addMonths(($i - 1) * 12 / $installments)->startOfYear()->addMonths($i - 1)->addDays(19);

                                        // The rest of your code remains unchanged
                                        $formattedDueDate = $dueDate->format('F');
                                        $formattedDescription = $feeHead->aca_feehead_description;

                                        if ($installments > 1) {
                                            $formattedDescription .= " ($formattedDueDate)";
                                        }

                                        FeeCollection::create([
                                            'fee_collection_hash_id' => md5(uniqid(rand(), true)),
                                            'std_id' => $std_id,
                                            'academic_year' => $request->input('academic_year'),
                                            'fee_group_id' => $groups->id,
                                            'aca_feehead_id' => $feeId,
                                            'aca_feeamount_id' => $feeAmount->id,
                                            'payable_amount' => $feeAmount->amount,
                                            'is_paid' => false,
                                            'due_date' => $dueDate,
                                            'fee_description' => $formattedDescription,
                                            'fee_collection_status' => 1,
                                            'school_id' => auth()->user()->school_id,
                                        ]);
                                    }

                                }
                            }
                        }
                    }

                    // Actual Works end
                }
            }

            // Commit the database transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.std_add_msg'), 'redirect' => 'admin/bulk-student-admission']);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            return response()->json(['code' => 0, 'msg' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    public function stdlist()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        $feegroups = AcademicFeeGroup::get()->where('aca_group_status', 1);
        return view('dashboard.admin.StudentManagement.student_list', compact('versions', 'feegroups'));
    }

    public function bulkstdAdmission2(Request $request)
    {
        if ($request->hasFile('upload')) {
            $csv = Reader::createFromPath($request->file('upload')->getPathname(), 'r');

            $csv->setHeaderOffset(0);
            $data = $csv->getRecords();
            foreach ($data as $record) {
                echo $record['name'];
            }
        }
    }

    public function getstdlist(Request $request)
    {
        $whr = [
            'academic_students.academic_year' => $request->academic_year,
            'academic_students.version_id' => $request->version_id,
            'academic_students.class_id' => $request->class_id,
            'academic_students.section_id' => $request->section_id,
            'academic_students.st_aca_status' => 1,
            'students.std_category' => $request->std_category,
        ];

        $whr = array_filter($whr);

        $academicStudents = DB::table('academic_students')
            ->where($whr)
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->join('edu_versions', 'academic_students.version_id', '=', 'edu_versions.id')
            ->join('edu_classes', 'academic_students.class_id', '=', 'edu_classes.id')
            ->join('sections', 'academic_students.section_id', '=', 'sections.id')
            ->select(
                'academic_students.id',
                'academic_students.academic_year',
                'academic_students.std_id',
                'students.std_name',
                'students.std_hash_id',
                'students.std_name_bn',
                'edu_versions.version_name',
                'edu_classes.class_name',
                'sections.section_name'
            )
            ->get();

        return response()->json(['students' => $academicStudents]);
    }

    public function studentProfile($std_hash_id)
    {
        // $std_hash_id = 'cb81ff56a28af5660ffa97cdf7dfdaff';
        $whr = [

            'students.std_hash_id' => $std_hash_id,
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
        return view('dashboard.admin.StudentManagement.student_profile', compact('student', 'attendanceData', 'totalPresent', 'totalAbsent', 'totalLate', 'totalDues', 'detailedDues'));
    }

    public function editStudent($std_hash_id)
    {
        $versions = EduVersions::get()->where('version_status', 1);
        $feegroups = AcademicFeeGroup::get()->where('aca_group_status', 1);

        // Retrieve data from both tables
        $academicStudent = AcademicStudent::where('std_hash_id', $std_hash_id)->first();
        $student = Student::where('std_hash_id', $std_hash_id)->first();

        return view('dashboard.admin.StudentManagement.edit_student', compact('versions', 'feegroups', 'academicStudent', 'student'));
    }

    public function stdEdit(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                // 'std_id' => 'required|string|max:15|unique:students,std_id',
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
                'std_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'std_category' => 'required|string|max:15',
                // 'std_status' => 'required|in:0,1',
                // 'school_id' => 'required|exists:schools,id',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $stdHashId = $request->input('std_hash_id');
            $student = Student::where('std_hash_id', $stdHashId)->first();

            $dataToUpdate = [
                'std_name' => $request->input('std_name'),
                'std_name_bn' => $request->input('std_name_bn'),
                'section_id' => $request->input('section_id'),
                'admission_date' => $request->input('admission_date'),
                'std_phone' => $request->input('std_phone'),
                'std_phone1' => $request->input('std_phone1'),
                'std_fname' => $request->input('std_fname'),
                'std_mname' => $request->input('std_mname'),
                'std_dob' => $request->input('std_dob'),
                'std_gender' => $request->input('std_gender'),
                'std_email' => $request->input('std_email'),
                'blood_group' => $request->input('blood_group'),
                'std_present_address' => $request->input('std_present_address'),
                'std_permanent_address' => $request->input('std_permanent_address'),
                'std_f_occupation' => $request->input('std_f_occupation'),
                'std_m_occupation' => $request->input('std_m_occupation'),
                'f_yearly_income' => $request->input('f_yearly_income'),
                'std_gurdian_name' => $request->input('std_gurdian_name'),
                'std_gurdian_relation' => $request->input('std_gurdian_relation'),
                'std_gurdian_mobile' => $request->input('std_gurdian_mobile'),
                'std_gurdian_address' => $request->input('std_gurdian_address'),
                'std_category' => $request->input('std_category'),
                'std_status' => 1,
            ];

            // Check if a new file is being uploaded
            if ($request->hasFile('std_picture')) {
                // Delete the old file
                if ($student->std_picture) {
                    $oldFilePath = 'public/img/std_img/' . $student->std_picture;
                    if (Storage::exists($oldFilePath)) {
                        Storage::delete($oldFilePath);
                    }
                }

                // Upload the new file
                $path = 'img/std_img/';
                $file = $request->file('std_picture');
                $fileExtension = $file->getClientOriginalExtension();
                $file_name = $stdHashId . '.' . $fileExtension;
                $upload = $file->storeAs($path, $file_name, 'public');

                // Add the image field to the data to update
                $dataToUpdate['std_picture'] = $file_name;
            }
            // Update or insert the student record
            Student::updateOrInsert(['std_hash_id' => $stdHashId], $dataToUpdate);



            $studentaca = [
                'section_id' => $request->input('section_id'),
                'roll_no' => 1,
                'st_aca_status' => 1,
            ];
            // Save the student
            AcademicStudent::updateOrInsert(['std_hash_id' => $stdHashId], $studentaca);


            // Commit the database transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.std_add_msg'), 'redirect' => 'admin/student-list']);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            return response()->json(['code' => 0, 'msg' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    public function enroll()
    {
        $send['students'] = AppliedStudent::get();
        return view('dashboard.admin.StudentManagement.enroll', $send);
    }

    public function fullView($std_hash_id)
    {
        $versions = EduVersions::get()->where('version_status', 1);
        $feegroups = AcademicFeeGroup::get()->where('aca_group_status', 1);

        // Retrieve data from both tables
        $academicStudent = AcademicStudent::where('std_hash_id', $std_hash_id)->first();
        $student = AppliedStudent::where('std_hash_id', $std_hash_id)->first();

        return view('dashboard.admin.StudentManagement.appliedStd_fullview', compact('versions', 'feegroups', 'academicStudent', 'student'));
    }

    public function stdAppliedEdit(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                // 'std_id' => 'required|string|max:15|unique:students,std_id',
                'std_name' => 'required|string|max:100',
                'std_name_bn' => 'nullable|string|max:200',
                'academic_year' => 'required|string|size:4',
                'version_id' => 'required|exists:edu_versions,id',
                'class_id' => 'required|exists:edu_classes,id',
                // 'section_id' => 'required|exists:sections,id',
                // 'admission_date' => 'nullable|date',
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
                'std_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'std_category' => 'required|string|max:15',
                'std_status' => 'required|in:0,1,2',
                // 'school_id' => 'required|exists:schools,id',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $stdHashId = $request->input('std_hash_id');
            $student = AppliedStudent::where('std_hash_id', $stdHashId)->first();

            $dataToUpdate = [
                'std_name' => $request->input('std_name'),
                'std_name_bn' => $request->input('std_name_bn'),
                // 'section_id' => $request->input('section_id'),
                // 'admission_date' => $request->input('admission_date'),
                'std_phone' => $request->input('std_phone'),
                'std_phone1' => $request->input('std_phone1'),
                'std_fname' => $request->input('std_fname'),
                'std_mname' => $request->input('std_mname'),
                'std_dob' => $request->input('std_dob'),
                'std_gender' => $request->input('std_gender'),
                'std_email' => $request->input('std_email'),
                'blood_group' => $request->input('blood_group'),
                'std_present_address' => $request->input('std_present_address'),
                'std_permanent_address' => $request->input('std_permanent_address'),
                'std_f_occupation' => $request->input('std_f_occupation'),
                'std_m_occupation' => $request->input('std_m_occupation'),
                'f_yearly_income' => $request->input('f_yearly_income'),
                'std_gurdian_name' => $request->input('std_gurdian_name'),
                'std_gurdian_relation' => $request->input('std_gurdian_relation'),
                'std_gurdian_mobile' => $request->input('std_gurdian_mobile'),
                'std_gurdian_address' => $request->input('std_gurdian_address'),
                // 'std_category' => $request->input('std_category'),
                'std_status' => $request->input('std_status'),
            ];


            // Update or insert the student record
            AppliedStudent::updateOrInsert(['std_hash_id' => $stdHashId], $dataToUpdate);

            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.std_add_msg'), 'redirect' => 'admin/student-enroll']);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            return response()->json(['code' => 0, 'msg' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }
}
