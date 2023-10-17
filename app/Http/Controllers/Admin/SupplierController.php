<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\Category;
use App\Models\Admin\Location;
use App\Models\Admin\Supplier;
use App\Models\Admin\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;
use Datatables;

class SupplierController extends Controller
{

    ######    Supplier Management Starts    ######

    public function supplierlist()
    {
        return view('dashboard.admin.supplierManagement.supplier-list');
    }

    public function addSupplier(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'supplier_name.required' => 'Supplier Name is Required',
            'supplier_phone.required' => 'Supplier Phone is Required',
            'supplier_address.required' => 'Supplier Address is Required',
            'supplier_phone.min' => 'Enter minimum 11 numbers',

        ];
        $validator = \Validator::make($request->all(), [
            'supplier_name' => [
                'required',
                Rule::unique('suppliers')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),

            ],
            'supplier_phone' => 'required|min:11|max:15',
            'supplier_address' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $accounttype = new Account_Types();
            $accounttype->account_type_hash_id =  md5(uniqid(rand(), true));
            $accounttype->account_head_id = 2;
            $accounttype->account_name = $request->supplier_name;
            $accounttype->store_id = \Auth::guard('admin')->user()->store_id;
            $accounttype->is_money = 0;
            $accounttype->code = Account_Types::where(['account_head_id' => 2])->max('code') + 1;
            $accounttype->normal = -1;
            $accounttype->acctype_status = 1;
            $accounttype->acc_type = 'supplier';
            $query = $accounttype->save();

            $supplier = new Supplier();
            $supplier->supplier_hash_id =  md5(uniqid(rand(), true));
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_address = $request->supplier_address;
            $supplier->supplier_phone = $request->supplier_phone;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->parent_id = $accounttype->code;
            $supplier->store_id = \Auth::guard('admin')->user()->store_id;
            $supplier->supplier_status = 1;
            $query = $supplier->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Supplier has been successfully saved']);
            }
        }
    }

    // GET ALL Category
    public function getSuppliersList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $suppliers = Supplier::select('id', 'supplier_name', 'supplier_address', 'supplier_phone', 'supplier_email', 'supplier_status')->where('store_id', $store_id)->get();
        return datatables()::of($suppliers)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['supplier_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editSupplierBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteSupplierBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Category DETAILS
    public function getSupplierDetails(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $supplierDetails = Supplier::find($supplier_id);
        return response()->json(['details' => $supplierDetails]);
    }


    //UPDATE Category DETAILS
    public function updateSupplierDetails(Request $request)
    {
        $supplier_id = $request->sid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'supplier_name.required' => 'Supplier Name is Required',
            'supplier_phone.required' => 'Supplier Phone is Required',
            'supplier_address.required' => 'Supplier Address is Required',
            'supplier_phone.min' => 'Enter minimum 11 numbers',

        ];
        $validator = \Validator::make($request->all(), [

            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $supplier_id,
            'supplier_phone' => 'required|min:11|max:15',
            'supplier_address' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {




            $supplier = Supplier::find($supplier_id);
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_address = $request->supplier_address;
            $supplier->supplier_phone = $request->supplier_phone;
            $supplier->supplier_email = $request->supplier_email;
            $query = $supplier->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Supplier Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Category RECORD
    public function deleteSupplier(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $query = Supplier::find($supplier_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Supplier has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
