<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Store;
use App\Models\Admin\Supplier;
use App\Models\Admin\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function unitlist()
    {
        $send['units'] = Unit::get();
        return view('dashboard.admin.inventory.unit', $send);
    }

    public function addUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|max:255',
            'unit_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $unit = new Unit();
            $unit->unit_hash_id = md5(uniqid(rand(), true));
            $unit->unit_name = $request->input('unit_name');
            $unit->unit_status = $request->input('unit_status');
            $query = $unit->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.unit_add_msg') , 'redirect'=> 'admin/unit-list']);
            }
        }
    }

    public function getUnitDetails(Request $request)
    {
        $unit_id = $request->unit_id;
        $unitDetails = Unit::find($unit_id);
        return response()->json(['details' => $unitDetails]);
    }

    //UPDATE Category DETAILS
    public function updateUnitDetails(Request $request)
    {
        $unit_id = $request->vid;
        $unit = Unit::find($unit_id);

        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|max:255|unique:units,unit_name,' . $unit_id,
            'unit_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $unit->unit_name = $request->input('unit_name');
            $unit->unit_status = $request->input('unit_status');
            $query = $unit->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.unit_edit_msg') , 'redirect'=> 'admin/unit-list']);
            }
        }
    }

    public function deleteUnit(Request $request)
    {
        $unit_id = $request->unit_id;
        $query = Unit::find($unit_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.unit_del_msg') , 'redirect' => 'admin/unit-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function storelist()
    {
        $send['stores'] = Store::get();
        return view('dashboard.admin.inventory.store', $send);
    }

    public function addStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|string|max:255',
            'store_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $store = new Store();
            $store->store_hash_id = md5(uniqid(rand(), true));
            $store->store_name = $request->input('store_name');
            $store->store_status = $request->input('store_status');
            $query = $store->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.store_add_msg') , 'redirect'=> 'admin/store-list']);
            }
        }
    }

    public function getStoreDetails(Request $request)
    {
        $store_id = $request->store_id;
        $storeDetails = Store::find($store_id);
        return response()->json(['details' => $storeDetails]);
    }

    //UPDATE Category DETAILS
    public function updateStoreDetails(Request $request)
    {
        $store_id = $request->vid;
        $store = Store::find($store_id);

        $validator = Validator::make($request->all(), [
            'store_name' => 'required|string|max:255|unique:stores,store_name,' . $store_id,
            'store_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $store->store_name = $request->input('store_name');
            $store->store_status = $request->input('store_status');
            $query = $store->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.store_edit_msg') , 'redirect'=> 'admin/store-list']);
            }
        }
    }

    public function deleteStore(Request $request)
    {
        $store_id = $request->store_id;
        $query = Store::find($store_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.store_del_msg') , 'redirect' => 'admin/store-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function supplierlist()
    {
        $send['suppliers'] = Supplier::get();
        return view('dashboard.admin.inventory.supplier', $send);
    }

    public function addSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:255',
            'supplier_address' => 'required|string|max:255',
            'supplier_phone' => 'required|regex:/^[0-9]{11}$/|unique:suppliers', 
            'supplier_email' => 'nullable|email|max:255',
            'supplier_status' => 'required|integer',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $supplier = new Supplier();
            $supplier->supplier_hash_id = md5(uniqid(rand(), true));
            $supplier->supplier_name = $request->input('supplier_name');
            $supplier->supplier_address = $request->input('supplier_address');
            $supplier->supplier_email = $request->input('supplier_email');
            $supplier->supplier_phone = $request->input('supplier_phone');
            $supplier->supplier_status = $request->input('supplier_status');
            $query = $supplier->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.supplier_add_msg') , 'redirect'=> 'admin/supplier-list']);
            }
        }
    }

    public function getSupplierDetails(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $supplierDetails = Supplier::find($supplier_id);
        return response()->json(['details' => $supplierDetails]);
    }

    //UPDATE Category DETAILS
    public function updateSupplierDetails(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $supplier = Supplier::find($supplier_id);

        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:255|unique:suppliers,supplier_name,' . $supplier->id,
    'supplier_address' => 'required|string|max:255',
    'supplier_phone' => 'required|regex:/^[0-9]{11}$/|unique:suppliers,supplier_phone,' . $supplier->id,
    'supplier_email' => 'nullable|email|max:255',
    'supplier_status' => 'required|integer',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $supplier->supplier_name = $request->input('supplier_name');
            $supplier->supplier_address = $request->input('supplier_address');
            $supplier->supplier_email = $request->input('supplier_email');
            $supplier->supplier_phone = $request->input('supplier_phone');
            $supplier->supplier_status = $request->input('supplier_status');
            $query = $supplier->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.supplier_edit_msg') , 'redirect'=> 'admin/supplier-list']);
            }
        }
    }

    public function deleteSupplier(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $query = Supplier::find($supplier_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.supplier_del_msg') , 'redirect' => 'admin/supplier-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
