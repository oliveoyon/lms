<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\InvCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvCategoryController extends Controller
{
    public function categorylist()
    {
        $send['categories'] = InvCategory::get();
        return view('dashboard.admin.inventory.category', $send);
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:inv_categories',
            'status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $category = new InvCategory();
            $category->category_hash_id = md5(uniqid(rand(), true));
            $category->category_name = $request->input('category_name');
            $category->status = $request->input('status');
            $query = $category->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.category_add_msg') , 'redirect'=> 'admin/category-list']);
            }
        }
    }

    public function getCategoryDetails(Request $request)
    {
        $category_id = $request->category_id;
        $categoryDetails = InvCategory::find($category_id);
        return response()->json(['details' => $categoryDetails]);
    }

    //UPDATE Category DETAILS
    public function updateCategoryDetails(Request $request)
    {
        $category_id = $request->vid;
        $category = InvCategory::find($category_id);

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:inv_categories,category_name,' . $category_id,
            'status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $category->category_name = $request->input('category_name');
            $category->status = $request->input('status');
            $query = $category->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.category_edit_msg') , 'redirect'=> 'admin/category-list']);
            }
        }
    }

    public function deleteCategory(Request $request)
    {
        $category_id = $request->category_id;
        $query = InvCategory::find($category_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.category_del_msg') , 'redirect' => 'admin/category-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
