<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{

    ######    Customer Management Starts    ######

    public function customerlist()
    {
        return view('dashboard.admin.customerManagement.customer-list');
    }

    public function addCustomer(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'customer_name.required' => 'Customer Name is Required',
            'customer_phone.required' => 'Customer Phone is Required',
            'customer_address.required' => 'Customer Address is Required',
            'customer_phone.min' => 'Enter minimum 11 numbers',

        ];
        $validator = \Validator::make($request->all(), [
            'customer_name' => [
                'required',
                Rule::unique('customers')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),

            ],
            'customer_phone' => 'required|min:11|max:15',
            'customer_address' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $accounttype = new Account_Types();
            $accounttype->account_type_hash_id =  md5(uniqid(rand(), true));
            $accounttype->account_head_id = 1;
            $accounttype->account_name = $request->customer_name;
            $accounttype->store_id = \Auth::guard('admin')->user()->store_id;
            $accounttype->is_money = 0;
            $accounttype->code = Account_Types::where(['account_head_id' => 1])->max('code') + 1;
            $accounttype->normal = 1;
            $accounttype->acc_type = 'customer';
            $accounttype->acctype_status = 1;
            $query = $accounttype->save();

            $customer = new Customer();
            $customer->customer_hash_id =  md5(uniqid(rand(), true));
            $customer->customer_name = $request->customer_name;
            $customer->customer_address = $request->customer_address;
            $customer->customer_phone = $request->customer_phone;
            $customer->customer_email = $request->customer_email;
            $customer->store_id = \Auth::guard('admin')->user()->store_id;
            $customer->customer_status = 1;
            $customer->parent_id = $accounttype->code;
            $query = $customer->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Customer has been successfully saved']);
            }
        }
    }

    // GET ALL Category
    public function getCustomersList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $customers = Customer::select('id', 'customer_name', 'customer_address', 'customer_phone', 'customer_email', 'customer_status')->where('store_id', $store_id)->get();
        return datatables()::of($customers)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['customer_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editCustomerBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteCustomerBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Category DETAILS
    public function getCustomerDetails(Request $request)
    {
        $customer_id = $request->customer_id;
        $customerDetails = Customer::find($customer_id);
        return response()->json(['details' => $customerDetails]);
    }


    //UPDATE Category DETAILS
    public function updateCustomerDetails(Request $request)
    {
        $customer_id = $request->sid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'customer_name.required' => 'Customer Name is Required',
            'customer_phone.required' => 'Customer Phone is Required',
            'customer_address.required' => 'Customer Address is Required',
            'customer_phone.min' => 'Enter minimum 11 numbers',

        ];
        $validator = \Validator::make($request->all(), [

            'customer_name' => 'required|unique:customers,customer_name,' . $customer_id,
            'customer_phone' => 'required|min:11|max:15',
            'customer_address' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $customer = Customer::find($customer_id);
            $customer->customer_name = $request->customer_name;
            $customer->customer_address = $request->customer_address;
            $customer->customer_phone = $request->customer_phone;
            $customer->customer_email = $request->customer_email;
            $query = $customer->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Customer Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Category RECORD
    public function deleteCustomer(Request $request)
    {
        $customer_id = $request->customer_id;
        $query = Customer::find($customer_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Customer has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
