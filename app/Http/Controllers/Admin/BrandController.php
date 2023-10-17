<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{

    ######    Brand Management Starts    ######

    public function brandlist()
    {
        return view('dashboard.admin.brandManagement.brand-list');
    }

    public function addBrand(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'brand_name.required' => 'Brand Name is Required',
            // 'brand_phone.required' => 'Brand Phone is Required',
            // 'brand_address.required' => 'Brand Address is Required',
            // 'brand_phone.min' => 'Enter minimum 11 numbers',

        ];
        $validator = \Validator::make($request->all(), [
            'brand_name' => [
                'required',
                Rule::unique('brands')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),

            ],

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $brand = new Brand();
            $brand->brand_hash_id =  md5(uniqid(rand(), true));
            $brand->brand_name = $request->brand_name;
            $brand->brand_address = $request->brand_address;
            $brand->brand_phone = $request->brand_phone;
            $brand->brand_email = $request->brand_email;
            $brand->store_id = \Auth::guard('admin')->user()->store_id;
            $brand->brand_status = 1;
            $query = $brand->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Brand has been successfully saved']);
            }
        }
    }

    // GET ALL Category
    public function getBrandsList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $brands = Brand::select('id', 'brand_name', 'brand_email', 'brand_phone', 'brand_address', 'brand_status')->where('store_id', $store_id)->get();
        return datatables()::of($brands)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['brand_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editBrandBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteBrandBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Category DETAILS
    public function getBrandDetails(Request $request)
    {
        $brand_id = $request->brand_id;
        $brandDetails = Brand::find($brand_id);
        return response()->json(['details' => $brandDetails]);
    }


    //UPDATE Category DETAILS
    public function updateBrandDetails(Request $request)
    {
        $brand_id = $request->sid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'brand_name.required' => 'Brand Name is Required',


        ];
        $validator = \Validator::make($request->all(), [

            'brand_name' => 'required|unique:brands,brand_name,' . $brand_id,

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $brand = Brand::find($brand_id);
            $brand->brand_name = $request->brand_name;
            $brand->brand_address = $request->brand_address;
            $brand->brand_phone = $request->brand_phone;
            $brand->brand_email = $request->brand_email;
            $query = $brand->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Brand Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Category RECORD
    public function deleteBrand(Request $request)
    {
        $brand_id = $request->brand_id;
        $query = Brand::find($brand_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Brand has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
