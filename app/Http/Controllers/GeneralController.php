<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\AppliedStudent;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;


class GeneralController extends Controller
{
    public function admissionApply()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('admission_form', compact('versions'));
    }

    public function stdApply(Request $request)
    {
        // Wrap the whole process in a database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                // 'std_id' => 'required|string|max:15|unique:students,std_id',
                'std_name' => 'required|string|max:100',
                'std_birth_reg' => 'required|string|max:50',
                'std_name_bn' => 'nullable|string|max:200',
                'academic_year' => 'required|string|size:4',
                'version_id' => 'required|exists:edu_versions,id',
                'class_id' => 'required|exists:edu_classes,id',
                'admission_date' => 'nullable|date',
                'std_phone' => 'required|string|max:11|unique:applied_students,std_phone',
                'std_phone1' => 'nullable|string|max:15',
                'std_fname' => 'required|string|max:100',
                'std_mname' => 'required|string|max:100',
                'std_dob' => 'required|date',
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
                'std_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // If validation fails, throw an exception
            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }


            if ($request->hasFile('std_picture')) {
                $path = 'img/applied_std_img/';
                $file = $request->file('std_picture');
                $fileExtension = $file->getClientOriginalExtension(); // Get the file extension
                $file_name = time() . '.' . $fileExtension;
                $upload = $file->storeAs($path, $file_name, 'public');
            } else {
                $file_name = '';
            }

            $std_hash_id = md5(uniqid(rand(), true));

            $student = new AppliedStudent([
                'std_hash_id' => $std_hash_id,
                'std_name' => $request->input('std_name'),
                'std_name_bn' => $request->input('std_name_bn'),
                'academic_year' => $request->input('academic_year'),
                'version_id' => $request->input('version_id'),
                'class_id' => $request->input('class_id'),
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
                'std_picture' => $file_name,
                'std_birth_reg' => $request->input('std_birth_reg'),
                'std_category' => $request->input('std_category'),
                'std_status' => 0,
                'school_id' => 101,
            ]);
            // Save the student
            $student->save();

            // Commit the database transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => 'Application has been submitted successfully', 'redirect' => 'getslip/' . $request->input('std_phone')]);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            return response()->json(['code' => 0, 'msg' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    public function getClassesByVersion(Request $request)
    {
        $versionId = $request->input('version_id');
        $classes = EduClasses::where('version_id', $versionId)->get();

        return response()->json(['classes' => $classes]);
    }

    public function getslip($std_hash_id)
    {

        $user = DB::table('applied_students')
            ->where('std_phone', $std_hash_id)
            ->join('edu_classes', 'applied_students.class_id', '=', 'edu_classes.id')
            // ->join('edu_versions', 'applied_students.version_id', '=', 'edu_versions.id')
            ->select('applied_students.*', 'edu_classes.class_name')
            ->first();

            if (!$user) {
                return redirect('/admission-form')->withErrors(['error' => 'No data found.']);
            }

        // dd($user);

        $data = [
            'user' => $user
        ];

        $pdf = PDF::loadView('print_admission_form', $data);
        $pdf->output();

        return $pdf->stream('document.pdf');

        // $mpdf = new \Mpdf\Mpdf();

        // // $mpdf->WriteHTML('<img src="https://via.placeholder.com/150" alt="">');
        // $mpdf->Image('https://via.placeholder.com/150', 0, 0, 210, 297, 'jpg', '', true, false);
        // $mpdf->Output();
    }

    public function getslip1($std_hash_id)
    {

        $user = DB::table('applied_students')
            ->where('std_phone', $std_hash_id)
            ->join('edu_classes', 'applied_students.class_id', '=', 'edu_classes.id')
            // ->join('edu_versions', 'applied_students.version_id', '=', 'edu_versions.id')
            ->select('applied_students.*', 'edu_classes.class_name')
            ->first();


        $data = [
            'user' => $user
        ];

        $htmlContent = \View::make('print_admission_form', $data)->render();

        return response()->json(['htmlContent' => $htmlContent]);

    }
}
