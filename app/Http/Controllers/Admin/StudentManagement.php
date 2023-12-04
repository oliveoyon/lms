<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeAmount;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\AcademicFeeHead;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\EduVersions;
use App\Models\Admin\FeeCollection;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use League\Csv\Reader;

class StudentManagement extends Controller
{
    public function admission()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        $feegroups = AcademicFeeGroup::get()->where('aca_group_status', 1);
        return view('dashboard.admin.StudentManagement.student_admission', compact( 'versions', 'feegroups'));
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
                $path = 'img/menu_img/';
                $file = $request->file('std_picture');
                $fileExtension = $file->getClientOriginalExtension(); // Get the file extension
                $file_name = $std_id . '.' . $fileExtension;
                $upload = $file->storeAs($path, $file_name, 'public');
            }else{
                $file_name = '';
            }
            

            $student = new Student([
                'std_hash_id' => md5(uniqid(rand(), true)),
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
                'school_id' => 100,
            ]);
            // Save the student
            $student->save();

            $studentaca = new AcademicStudent([
                'std_hash_id' => md5(uniqid(rand(), true)),
                'std_id' => $std_id,
                'academic_year' => $request->input('academic_year'),
                'version_id' => $request->input('version_id'),
                'class_id' => $request->input('class_id'),
                'section_id' => $request->input('section_id'),
                'std_password' => Hash::make($std_id),
                'roll_no' => 1,
                'st_aca_status' => 1,
                'school_id' => 100,
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
                                    'amount_paid' => 0,
                                    'due_date' => $dueDate,
                                    'fee_description' => $formattedDescription,
                                    'fee_collection_status' => 1,
                                    'school_id' => 1,
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
        return view('dashboard.admin.StudentManagement.bulkstudent_admission', compact( 'versions', 'feegroups'));
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
                // 'std_status' => 'required|in:0,1',
                // 'school_id' => 'required|exists:schools,id',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            if ($request->hasFile('upload')) {
                $csv = Reader::createFromPath($request->file('upload')->getPathname(), 'r');
                
                $csv->setHeaderOffset(0);
                $data = $csv->getRecords();
                foreach ($data as $record) {
                    // Actual Works Start individual task
                    $lastTwoDigits = substr($request->input('academic_year'), -2);
                    $std_id = $lastTwoDigits . str_pad(Student::max('id') + 1, 3, '0', STR_PAD_LEFT);
                    
                    $student = new Student([
                        'std_hash_id' => md5(uniqid(rand(), true)),
                        'std_id' => $std_id,
                        'std_name' => $record['std_name'],
                        'std_name_bn' => $record['std_name_bn'],
                        'academic_year' => $request->input('academic_year'),
                        'version_id' => $request->input('version_id'),
                        'class_id' => $request->input('class_id'),
                        'section_id' => $request->input('section_id'),
                        'admission_date' => $request->input('admission_date'),
                        'std_phone' => $record['std_phone'],
                        'std_phone1' => $record['std_phone1'],
                        'std_fname' => $record['std_fname'],
                        'std_mname' => $record['std_mname'],
                        'std_dob' => Carbon::createFromFormat('m/d/Y', $record['std_dob'])->format('Y-m-d'),
                        'std_gender' => $record['std_gender'],
                        'std_email' => $record['std_email'],
                        'blood_group' => $record['blood_group'],
                        'std_present_address' => $record['std_present_address'],
                        'std_permanent_address' => $record['std_permanent_address'],
                        'std_f_occupation' => $record['std_f_occupation'],
                        'std_m_occupation' => $record['std_m_occupation'],
                        'f_yearly_income' => $record['f_yearly_income'],
                        'std_gurdian_name' => $record['std_gurdian_name'],
                        'std_gurdian_relation' => $record['std_gurdian_relation'],
                        'std_gurdian_mobile' => $record['std_gurdian_mobile'],
                        'std_gurdian_address' => $record['std_gurdian_address'],
                        'std_picture' => $record['std_picture'],
                        'std_category' => $request->input('std_category'),
                        'std_status' => 1,
                        'school_id' => 100,
                    ]);
                    // Save the student
                    $student->save();
        
                    $studentaca = new AcademicStudent([
                        'std_hash_id' => md5(uniqid(rand(), true)),
                        'std_id' => $std_id,
                        'academic_year' => $request->input('academic_year'),
                        'version_id' => $request->input('version_id'),
                        'class_id' => $request->input('class_id'),
                        'section_id' => $request->input('section_id'),
                        'std_password' => Hash::make($std_id),
                        'roll_no' => 1,
                        'st_aca_status' => 1,
                        'school_id' => 100,
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
                                                'amount_paid' => 0,
                                                'due_date' => $dueDate,
                                                'fee_description' => $formattedDescription,
                                                'fee_collection_status' => 1,
                                                'school_id' => 1,
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




}
