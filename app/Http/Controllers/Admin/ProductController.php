<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\InvCategory;
use App\Models\Admin\Product;
use App\Models\Admin\Teacher;
use App\Models\Admin\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productList()
    {
        $products = Product::with(['category', 'unit', 'supplier'])->get();
        $categories = InvCategory::get()->where('status', 1);
        $versions = Unit::get()->where('version_status', 1);
        $teachers = Teacher::get()->where('teacher_status', 1);

        return view('dashboard.admin.academic.product', compact('products', 'classes', 'versions', 'teachers'));
    }


    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100|unique:products,product_name,NULL,id,class_id,' . $request->input('class_id'),
            'version_id' => 'required|exists:edu_versions,id',
            'class_id' => 'required|exists:edu_classes,id',
            'max_students' => 'required',
            // 'class_teacher_id' => 'required|exists:teachers,id',
            'product_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $product = new Product();
        $product->product_hash_id = md5(uniqid(rand(), true));
        $product->product_name = $request->input('product_name');
        $product->version_id = $request->input('version_id');
        $product->class_id = $request->input('class_id');
        $product->max_students = $request->input('max_students');
        $product->class_teacher_id = $request->input('class_teacher_id');
        $product->product_status = $request->input('product_status');
        $query = $product->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.product_add_msg') , 'redirect' => 'admin/product-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function getProductDetails(Request $request)
    {
        $product_id = $request->product_id;
        $productDetails = Product::find($product_id);
        // dd($productDetails);
        return response()->json(['details' => $productDetails]);
    }

    public function updateProductDetails(Request $request)
    {
        $product_id = $request->sid;
        $product = Product::find($product_id);
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100|unique:products,product_name,' . $product_id . ',id,class_id,' . ($product ? $product->class_id : null),
            'version_id' => 'required|exists:edu_versions,id',
            'max_students' => 'required',
            'product_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $product->product_name = $request->input('product_name');
        $product->version_id = $request->input('version_id');
        $product->class_teacher_id = $request->input('class_teacher_id');
        $product->class_id = $request->input('class_id');
        $product->max_students = $request->input('max_students');
        $product->product_status = $request->input('product_status');

        $query = $product->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.product_edit_msg') , 'redirect' => 'admin/product-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteProduct(Request $request)
    {
        $product_id = $request->product_id;
        $query = Product::find($product_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.product_del_msg') , 'redirect' => 'admin/product-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
