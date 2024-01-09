<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\GeneralSetting;
use Illuminate\Http\Request;
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



}
