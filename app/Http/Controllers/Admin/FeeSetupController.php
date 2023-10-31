<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeAmount;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\AcademicFeeHead;
use App\Models\Admin\EduClasses;
use App\Models\Admin\FeeFrequency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FeeSetupController extends Controller
{
    public function multiform()
    {
        return view('dashboard.admin.FeeSetup.multiform');
    }
    
    public function feeFrequencyList()
    {
        $send['feeFrequencies'] = FeeFrequency::get();
        return view('dashboard.admin.FeeSetup.feefreq', $send);
    }


    public function addFeeFrequency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'freq_name' => 'required|string|max:255|unique:fee_frequencies', 
            'no_of_installment' => 'required|integer',
            'installment_period' => 'nullable|string|max:30',
            'freq_status' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $feeFrequency = new FeeFrequency();
        $feeFrequency->freq_hash_id = Str::random(32); // Generate a unique hash ID
        $feeFrequency->freq_name = $request->input('freq_name');
        $feeFrequency->no_of_installment = $request->input('no_of_installment');
        $feeFrequency->installment_period = $request->input('installment_period');
        $feeFrequency->freq_status = $request->input('freq_status');

        if ($feeFrequency->save()) {
            return response()->json(['code' => 1, 'msg' => 'Fee frequency has been successfully added', 'redirect' => 'admin/fee-frequency-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function getFeeFrequencyDetails(Request $request)
    {
        $feeFrequencyId = $request->fee_frequency_id;
        $feeFrequency = FeeFrequency::find($feeFrequencyId);
        
        return response()->json(['details' => $feeFrequency]);
    }

    public function updateFeeFrequencyDetails(Request $request)
    {
        $feeFrequencyId = $request->fee_frequency_id;
        $feeFrequency = FeeFrequency::find($feeFrequencyId);

        $validator = Validator::make($request->all(), [
            'freq_name' => 'required|string|max:255|unique:fee_frequencies,freq_name,'.$feeFrequencyId.',id', 
            'no_of_installment' => 'required|integer',
            'installment_period' => 'nullable|string|max:30',
            'freq_status' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $feeFrequency->freq_name = $request->input('freq_name');
        $feeFrequency->no_of_installment = $request->input('no_of_installment');
        $feeFrequency->installment_period = $request->input('installment_period');
        $feeFrequency->freq_status = $request->input('freq_status');

        if ($feeFrequency->save()) {
            return response()->json(['code' => 1, 'msg' => 'Fee frequency has been successfully updated', 'redirect' => 'admin/fee-frequency-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteFeeFrequency(Request $request)
    {
        $feeFrequencyId = $request->fee_frequency_id;

        // Check if the fee frequency is used in academic_fee_heads
        $usedInAcademicFeeHeads = AcademicFeeHead::where('aca_feehead_freq', $feeFrequencyId)->exists();

        if (!$usedInAcademicFeeHeads) {
            $feeFrequency = FeeFrequency::find($feeFrequencyId);

            if ($feeFrequency) {
                $feeFrequency->delete();
                return response()->json(['code' => 1, 'msg' => 'Fee frequency has been deleted from the database', 'redirect' => 'admin/fee-frequency-list']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        } else {
            return response()->json(['code' => 0, 'msg' => 'Cannot delete the fee frequency as it is used in academic fee heads.']);
        }
    }


    public function academicFeeHeadList()
    {
        $send['academicFeeHeads'] = AcademicFeeHead::all();
        $send['feeFrequencies'] = FeeFrequency::get()->where('freq_status', 1);
        return view('dashboard.admin.FeeSetup.feehead', $send);
    }

    public function addAcademicFeeHead(Request $request)
    {
        $no_of_installment = FeeFrequency::find($request->input('aca_feehead_freq'))->no_of_installment;
        $validator = Validator::make($request->all(), [
            'aca_feehead_name' => 'required|string|max:255|unique:academic_fee_heads,aca_feehead_name', 
            'aca_feehead_description' => 'required|string',
            'aca_feehead_freq' => 'required|integer',
            'status' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $academicFeeHead = new AcademicFeeHead();
            $academicFeeHead->aca_feehead_hash_id = md5(uniqid(rand(), true));
            
            $academicFeeHead->aca_feehead_name = $request->input('aca_feehead_name');
            $academicFeeHead->aca_feehead_description = $request->input('aca_feehead_description');
            $academicFeeHead->aca_feehead_freq = $request->input('aca_feehead_freq');
            $academicFeeHead->no_of_installment = $no_of_installment;
            $academicFeeHead->status = $request->input('status');

            $query = $academicFeeHead->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => __('language.academic_fee_head_add_msg'), 'redirect' => 'admin/academic-fee-head-list']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }



    public function getAcademicFeeHeadDetails(Request $request)
    {
        $academicFeeHeadId = $request->academic_feehead_id;
        $academicFeeHeadDetails = AcademicFeeHead::find($academicFeeHeadId);
        return response()->json(['details' => $academicFeeHeadDetails]);
    }

    public function updateAcademicFeeHeadDetails(Request $request)
    {
        $academicFeeHeadId = $request->fee_head_id;
        $academicFeeHead = AcademicFeeHead::find($academicFeeHeadId);
        $no_of_installment = FeeFrequency::find($request->input('aca_feehead_freq'))->no_of_installment;

        $validator = Validator::make($request->all(), [
            'aca_feehead_name' => 'required|string|max:255|unique:academic_fee_heads,aca_feehead_name,' . $academicFeeHeadId, // Ensure 'aca_feehead_name' is unique, excluding the current record with ID $id
            'aca_feehead_description' => 'required|string',
            'aca_feehead_freq' => 'required|integer',
            'status' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $academicFeeHead->aca_feehead_name = $request->input('aca_feehead_name');
            $academicFeeHead->aca_feehead_description = $request->input('aca_feehead_description');
            $academicFeeHead->aca_feehead_freq = $request->input('aca_feehead_freq');
            $academicFeeHead->no_of_installment = $no_of_installment;
            $academicFeeHead->status = $request->input('status');
            $query = $academicFeeHead->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => __('language.academic_fee_head_edit_msg'), 'redirect' => 'admin/academic-fee-head-list']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function deleteAcademicFeeHead(Request $request)
    {
        $academicFeeHeadId = $request->academic_feehead_id;
        
        // Check if the academic fee head is associated with any fee group
        $isUsedInFeeGroups = AcademicFeeGroup::where('aca_feehead_id', 'like', "%$academicFeeHeadId%")->exists();

        if ($isUsedInFeeGroups) {
            return response()->json(['code' => 0, 'msg' => 'This academic fee head is used in fee groups and cannot be deleted.']);
        }

        $query = AcademicFeeHead::find($academicFeeHeadId);

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Academic fee head not found']);
        }

        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.academic_fee_head_del_msg'), 'redirect' => 'admin/academic-fee-head-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }


    public function academicFeeGroupList()
    {
        $send['feeHeads'] = AcademicFeeHead::get()->where('status', 1);
        $academicFeeGroups = AcademicFeeGroup::get();

        $academicFeeGroups->each(function ($feeGroup) {
            $acaFeeheadIds = explode(',', $feeGroup->aca_feehead_id);

            // Retrieve the corresponding aca_feehead_name values
            $acaFeeheadNames = AcademicFeeHead::whereIn('id', $acaFeeheadIds)
                ->pluck('aca_feehead_name')
                ->implode(', ');

            // Replace the original aca_feehead_id with aca_feehead_names
            $feeGroup->aca_feehead_id = $acaFeeheadNames;
        });

        // Now, $academicFeeGroups contains aca_feehead_names instead of aca_feehead_ids
        $send['academicFeeGroups'] = $academicFeeGroups;
        return view('dashboard.admin.FeeSetup.feegroup', $send);
    }

    public function addAcademicFeeGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aca_group_name' => 'required|string|max:255|unique:academic_fee_groups,aca_group_name',
            'academic_year' => 'required|integer',
            'aca_group_status' => 'required|integer',
            'aca_feehead_ids' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    // Validate that each selected ID exists in the AcademicFeeHead table
                    $existingIds = AcademicFeeHead::pluck('id')->toArray();
                    foreach ($value as $selectedId) {
                        if (!in_array($selectedId, $existingIds)) {
                            $fail('One or more selected fee head IDs are invalid.');
                        }
                    }
                },
            ],

        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $feeGroup = new AcademicFeeGroup();
            $feeGroup->aca_group_hash_id = md5(uniqid(rand(), true));
            $feeGroup->aca_group_name = $request->input('aca_group_name');
            $feeGroup->academic_year = $request->input('academic_year');
            $feeGroup->aca_group_status = $request->input('aca_group_status');

            // Convert selected fee heads to comma-separated text
            $aca_feehead_ids = implode(',', $request->input('aca_feehead_ids'));
            $feeGroup->aca_feehead_id = $aca_feehead_ids;

            $query = $feeGroup->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => __('language.fee_group_add_msg'), 'redirect' => 'admin/academic-fee-group-list']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function getAcademicFeeGroupDetails(Request $request)
    {
        $feeGroupId = $request->fee_group_id;
        $feeGroupDetails = AcademicFeeGroup::find($feeGroupId);
        return response()->json(['details' => $feeGroupDetails]);
    }

    public function updateAcademicFeeGroupDetails(Request $request)
    {
        $feeGroupId = $request->fee_group_id;
        $feeGroup = AcademicFeeGroup::find($feeGroupId);

        $validator = Validator::make($request->all(), [
            'aca_group_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_fee_groups', 'aca_group_name')->ignore($feeGroupId),
            ],
            'academic_year' => 'required|integer',
            'aca_group_status' => 'required|integer',
            'aca_feehead_ids' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    // Validate that each selected ID exists in the AcademicFeeHead table
                    $existingIds = AcademicFeeHead::pluck('id')->toArray();
                    foreach ($value as $selectedId) {
                        if (!in_array($selectedId, $existingIds)) {
                            $fail('One or more selected fee head IDs are invalid.');
                        }
                    }
                },
            ],
            
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $feeGroup->aca_group_name = $request->input('aca_group_name');
            $feeGroup->academic_year = $request->input('academic_year');
            $feeGroup->aca_group_status = $request->input('aca_group_status');

            // Convert selected fee heads to comma-separated text
            $aca_feehead_ids = implode(',', $request->input('aca_feehead_ids'));
            $feeGroup->aca_feehead_id = $aca_feehead_ids;

            $query = $feeGroup->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => __('language.fee_group_edit_msg'), 'redirect' => 'admin/academic-fee-group-list']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function deleteAcademicFeeGroup(Request $request)
    {
        $feeGroupId = $request->fee_group_id;
        $query = AcademicFeeGroup::find($feeGroupId);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.fee_group_del_msg'), 'redirect' => 'admin/academic-fee-group-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function academicFeeAmountList()
    {
        $academicFeeAmounts = AcademicFeeAmount::with('academicFeeGroup', 'academicFeeHead', 'eduClass')->get();
        $feeGroups = AcademicFeeGroup::all();
        $classes = EduClasses::get()->where('class_status', 1);;
        return view('dashboard.admin.FeeSetup.feeamount', compact('academicFeeAmounts', 'feeGroups', 'classes'));
    }

    public function getFeeHeads(Request $request)
    {
        $feeGroupId = $request->feeGroupId;

        // Get the academic fee group for the given feeGroupId
        $academicFeeGroup = AcademicFeeGroup::find($feeGroupId);

        if (!$academicFeeGroup) {
            return response()->json(['feeHeads' => []]); // Return an empty array if fee group not found.
        }

        // Split the comma-separated fee head IDs and convert them to an array
        $feeHeadIds = explode(',', $academicFeeGroup->aca_feehead_id);

        // Fetch the fee heads associated with the IDs
        $feeHeads = AcademicFeeHead::whereIn('id', $feeHeadIds)->get();

        return response()->json(['feeHeads' => $feeHeads]);
    }

    public function addAcademicFeeAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aca_group_id' => 'required|integer',
            'academic_year' => 'required',
            'aca_feehead_id' => 'array',
            'class_id' => 'array',
            // 'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $aca_feehead_ids = $request->input('aca_feehead_id');
            $class_ids = $request->input('class_id');
            $academic_year = $request->input('academic_year');
            $aca_group_id = $request->input('aca_group_id');

            // Create an array to store the AcademicFeeAmount data
            $data = [];

            foreach ($class_ids as $class_id) {
                foreach ($aca_feehead_ids as $key => $val) {
                    // Check if a record with the same values already exists in the database
                    $existingRecord = AcademicFeeAmount::where([
                        'aca_group_id' => $aca_group_id,
                        'aca_feehead_id' => $key,
                        'class_id' => $class_id,
                        'academic_year' => $academic_year,
                    ])->first();

                    if (!$existingRecord) {
                        $data[] = [
                            'aca_feeamount_hash_id' => md5(uniqid(rand(), true)),
                            'aca_group_id' => $aca_group_id,
                            'aca_feehead_id' => $key,
                            'amount' => $val,
                            'class_id' => $class_id,
                            'academic_year' => $academic_year,
                            'aca_feeamount_status' => 1,
                        ];
                    }
                }
            }

            // Insert the data into the database
            $query = AcademicFeeAmount::insert($data);

            if ($query) {
                return response()->json(['code' => 1, 'msg' => __('language.academic_fee_head_add_msg'), 'redirect' => 'admin/academic-fee-amount-list']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }






}
