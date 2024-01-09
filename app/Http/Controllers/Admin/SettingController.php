<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function genSetting()
    {
        $settings = GeneralSetting::where('set_status', 1)->first();
        return view('dashboard.admin.settings.gen_setting', compact('settings'));
    }

    public function editGenSetting(Request $request)
    {
        $settingId = $request->sid;
        $setting = GeneralSetting::find($settingId);

        $request->merge(['enable_notifications' => (bool) $request->input('enable_notifications')]);

        $validator = Validator::make($request->all(), [
            'school_title' => 'required|string',
            'school_title_bn' => 'required|string',
            'school_short_name' => 'required|string',
            'school_code' => 'string',
            'school_eiin_no' => 'string',
            'school_email' => 'required|string',
            'school_phone' => 'required|string',
            'school_phone1' => 'string',
            'school_phone2' => 'string',
            'school_fax' => 'string',
            'school_address' => 'required|string',
            'school_country' => 'required|string',
            'currency_sign' => 'required|string',
            'currency_name' => 'required|string',
            'school_geocode' => 'string',
            'school_facebook' => 'string',
            'school_twitter' => 'string',
            'school_google' => 'string',
            'school_linkedin' => 'string',
            'school_youtube' => 'string',
            'school_copyrights' => 'required|string',
            'school_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100,max_width=500,max_height=500',
            'currency' => 'string',
            'set_status' => 'in:0,1',
            'timezone' => 'required|string',
            'language' => 'string',
            'enable_notifications' => 'boolean',
            'school_id' => 'integer',
        ]);


        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        if ($request->hasFile('school_logo')) {
            // Delete existing logo if it exists
            if ($setting->school_logo) {
                Storage::disk('public')->delete('img/logo/' . $setting->school_logo);
            }

            // Upload the new logo
            $path = 'img/logo/';
            $file = $request->file('school_logo');
            $fileExtension = $file->getClientOriginalExtension();
            $file_name = 'logo.' . $fileExtension;
            $upload = $file->storeAs($path, $file_name, 'public');

            // Update the logo field in the settings
            $file_name = $file_name;
        } else {
            // No new logo provided, keep the existing one
            $file_name = $setting->school_logo;
        }

        $setting->school_title = $request->input('school_title');
        $setting->school_title_bn = $request->input('school_title_bn');
        $setting->school_short_name = $request->input('school_short_name');
        $setting->school_code = $request->input('school_code');
        $setting->school_eiin_no = $request->input('school_eiin_no');
        $setting->school_email = $request->input('school_email');
        $setting->school_phone = $request->input('school_phone');
        $setting->school_phone1 = $request->input('school_phone1');
        $setting->school_phone2 = $request->input('school_phone2');
        $setting->school_fax = $request->input('school_fax');
        $setting->school_address = $request->input('school_address');
        $setting->school_country = $request->input('school_country');
        $setting->currency_sign = $request->input('currency_sign');
        $setting->currency_name = $request->input('currency_name');
        $setting->school_geocode = $request->input('school_geocode');
        $setting->school_facebook = $request->input('school_facebook');
        $setting->school_twitter = $request->input('school_twitter');
        $setting->school_google = $request->input('school_google');
        $setting->school_linkedin = $request->input('school_linkedin');
        $setting->school_youtube = $request->input('school_youtube');
        $setting->school_copyrights = $request->input('school_copyrights');
        $setting->school_logo = $file_name;
        $setting->currency = $request->input('currency');
        $setting->set_status = $request->input('set_status');
        $setting->timezone = $request->input('timezone');
        $setting->language = $request->input('language');
        $setting->enable_notifications = $request->input('enable_notifications');
        $setting->school_id = auth()->user()->school_id;

        $query = $setting->save();

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => __('language.settings_updated_msg'), 'redirect' => 'admin/general-settings']);
        }
    }


    public function generateBill(Request $request)
    {
        $dueReport = DB::table('fee_collections as fc')
            ->select(
                'fc.id as fee_collection_id',
                's.std_name',
                'fc.std_id',
                'fc.due_date',
                'fc.academic_year',
                'fc.payable_amount',
                'fc.fee_description',
                'fc.fee_collection_status',
                'fc.school_id',
                DB::raw('MONTH(fc.due_date) as due_month'),
                DB::raw('fc.payable_amount - COALESCE(SUM(fp.amount_paid), 0) as remaining_due_amount'),
                DB::raw('COALESCE(SUM(fp.amount_paid), 0) as amount_paid')
            )
            ->leftJoin('fee_payments as fp', 'fc.id', '=', 'fp.fee_collection_id')
            ->leftJoin('students as s', 'fc.std_id', '=', 's.std_id')
            // ->where('fc.is_paid', false)
            ->where('fc.std_id', $request->std_id);

        if (!is_null($request->ac)) {
            $dueReport->where('fc.academic_year', $request->ac);
        }

        if (!is_null($request->mon)) {
            $dueReport->whereMonth('fc.due_date', '<=', $request->mon);
        }

        $dueReport = $dueReport->groupBy('fc.id', 's.std_name', 'fc.std_id', 'fc.due_date', 'fc.academic_year', 'fc.payable_amount', 'fc.fee_description', 'fc.fee_collection_status', 'fc.school_id', 'due_month')
            ->orderBy('fc.due_date')
            ->orderBy('due_month')
            ->get();

        $html = '';
        $currentMonth = null;

        // Modal HTML (outside the loop)
        $html .= '<div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        $html .= '<div class="modal-dialog" role="document">';
        $html .= '<div class="modal-content">';
        $html .= '<div class="modal-header">';
        $html .= '<h5 class="modal-title" id="exampleModalLabel">Collect Bill</h5>';
        $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        $html .= '<span aria-hidden="true">&times;</span>';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '<div class="modal-body">';
        $html .= '<div id="modal-content"></div>'; // Container for dynamic content
        $html .= '</div>';
        $html .= '<div class="modal-footer">';
        $html .= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $monthTotal = 0;
        foreach ($dueReport as $row) {
            $month = date('F Y', strtotime($row->due_date));
            $monthTotal = $monthTotal + $row->remaining_due_amount;
            
            
            if ($currentMonth !== $month) {
                // Close the previous card if it's not the first one
                
                if ($currentMonth !== null) {
                    
                    $html .= '</table>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
                // Start a new card
                $html .= '<div class="card">';
                $html .= '<div class="card-header">' . $month .  '</div>';
                $html .= '<div class="card-body">';
                $html .= '<table class="table">';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th>Total Payable</th>';
                $html .= '<th>Amount Paid</th>';
                $html .= '<th>Remaining</th>';
                $html .= '<th>Description</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                if($monthTotal == 0){
                    $html .= '<h4 class="badge badge-success" style="font-size:14px;"><i class="far fa-clock"></i> Bill Paid for '.$month.'</h4>';
                    // $html .= '<button type="button" class="btn btn-primary open-modal-btn" data-toggle="modal" data-target="#billModal" data-month="' . $month . '" data-std-id="' . $row->std_id . '" data-academic-year="' . $row->academic_year . '">Collect Bill</button>';


                }else{
                    $html .= '<button type="button" class="btn btn-primary open-modal-btn" data-toggle="modal" data-target="#billModal" data-month="' . $month . '" data-std-id="' . $row->std_id . '" data-academic-year="' . $row->academic_year . '">Collect Bill</button>';
                    
                }
            }

            // Add row data to the current table
            $html .= '<tr>';
            $html .= '<td>' . $row->payable_amount . '</td>';
            $html .= '<td>' . $row->amount_paid . '</td>';
            $html .= '<td>' . $row->remaining_due_amount . '</td>';
            $html .= '<td>' . $row->fee_description . '</td>';
            $html .= '</tr>';

            $currentMonth = $month;
        }

        

        // Close the last card
        if ($currentMonth !== null) {
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';
        }

        // Return the HTML in a JSON response
        return response()->json(['html' => $html]);
    }

    public function fetchColletcData(Request $request)
    {
        // $requestData = $request->all();
        // $html = '<pre>' . htmlspecialchars(print_r($requestData, true)) . '</pre>';
        $numericMonth = date('n', strtotime($request->month));


        $dueReport = DB::table('fee_collections as fc')
            ->select(
                'fc.id as fee_collection_id',
                's.std_name',
                'fc.std_id',
                'fc.due_date',
                'fc.academic_year',
                'fc.payable_amount',
                'fc.fee_description',
                'fc.fee_collection_status',
                'fc.school_id',
                DB::raw('MONTH(fc.due_date) as due_month'),
                DB::raw('fc.payable_amount - COALESCE(SUM(fp.amount_paid), 0) as remaining_due_amount'),
                DB::raw('COALESCE(SUM(fp.amount_paid), 0) as amount_paid')
            )
            ->leftJoin('fee_payments as fp', 'fc.id', '=', 'fp.fee_collection_id')
            ->leftJoin('students as s', 'fc.std_id', '=', 's.std_id')
            ->where('fc.std_id', $request->stdId)
            ->where('fc.academic_year', $request->academicYear)
            ->whereMonth('fc.due_date', '<=', $numericMonth)
            ->groupBy('fc.id', 's.std_name', 'fc.std_id', 'fc.due_date', 'fc.academic_year', 'fc.payable_amount', 'fc.fee_description', 'fc.fee_collection_status', 'fc.school_id', 'due_month')
            ->orderBy('fc.due_date')
            ->orderBy('due_month')
            ->get();

        $html = '<pre>' . htmlspecialchars(print_r($dueReport, true)) . '</pre>';


        $html = '<form id="billForm" action="' . route('admin.collectBill') . '" method="POST">';
        $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
        $html .= '<table class="table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Payable Amount</th>';
        $html .= '<th>Paid Amount</th>';
        $html .= '<th>Remaining</th>';
        $html .= '<th>Description</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Loop through each row in the data
        foreach ($dueReport as $row) {
            if($row->remaining_due_amount == 0){continue;}
            $html .= '<tr>';
            $html .= '<td>' . $row->payable_amount . '</td>';
            $html .= '<td>' . $row->amount_paid . '</td>';
            $html .= '<td>' . $row->remaining_due_amount . '</td>';
            $html .= '<td>' . $row->fee_description . '</td>';
            $html .= '<input type="hidden" name="fee_collection_id[]" value="' . $row->fee_collection_id . '">';
            $html .= '<input type="hidden" name="remaining[]" value="' . $row->remaining_due_amount . '">';
            $html .= '<input type="hidden" name="std_id" value="' . $row->std_id . '">';
            $html .= '<input type="hidden" name="academic_year" value="' . $row->academic_year . '">';
            $html .= '</tr>';
        }

        // Add total row
        $html .= '<tr>';
        $html .= '<td colspan="2">Total</td>';
        $html .= '<td>' . $dueReport->sum('remaining_due_amount') . '</td>';

        $html .= '</tr>';

        // Add row for discount input


        // Add submit button
        if($dueReport->sum('remaining_due_amount') == 0) {
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<h2 style="bg-success">Already Paid</h2>';
        }else{
            $html .= '<tr>';
            $html .= '<td colspan="2">Discount:</td>';
            $html .= '<td><input type="text" name="discount" class="form-control"></td>';
            $html .= '<td></td>';
            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<button type="submit" class="btn btn-primary">Submit</button>';
        }

        $html .= '</form>';

        // Return the HTML in a JSON response
        return response()->json(['html' => $html]);
    }

    public function collectBill(Request $request)
    {
        $trnx_id = time();

        for ($i = 0; $i < count($request->fee_collection_id); $i++) {
            $feeCollectionId = $request->fee_collection_id[$i];

            if($request->remaining[$i] == 0){continue;}

            $updateData = [
                'is_paid' => 1,
            ];
            DB::table('fee_collections')->where('id', $feeCollectionId)->update($updateData);

            $paymentData = [
                'fee_collection_id' => $feeCollectionId,
                'trnx_id' => $trnx_id,
                'amount_paid' => $request->remaining[$i],
                'payment_date' => now(),
                'payment_method' => 'Cash',
                'status' => 1,
                'school_id' => auth()->user()->school_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert a new record into the fee_payments table
            DB::table('fee_payments')->insert($paymentData);
        }
    }
}
