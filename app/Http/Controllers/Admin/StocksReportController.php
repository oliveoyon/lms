<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocksReportController extends Controller
{
    public function stockReports()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $manufacturers = DB::table('manufacturers')->select('id', 'manufacturer_name')->where(['manufacturer_status' => 1, 'store_id' => $store_id])->get();
        $categories = DB::table('categories')->select('id', 'category_name')->where(['category_status' => 1, 'store_id' => $store_id])->get();
        $brands = DB::table('brands')->select('id', 'brand_name')->where(['brand_status' => 1, 'store_id' => $store_id])->get();
        $suppliers = DB::table('suppliers')->select('id', 'supplier_name')->where(['store_id' => $store_id])->get();
        $data = ['manufacturers' => $manufacturers, 'categories' => $categories, 'brands' => $brands, 'suppliers' => $suppliers];
        return view('dashboard.admin.reports.stocks.homereports', $data);
    }

    public function stockLists(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $whr = [
            'manufacturer_id' => $request->manufacturer_id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'store_id' => $store_id
        ];
        $whr = array_filter($whr);
        $products = DB::table('products')->where($whr)->get();
        dd($products);
        // ->where('product_stocks.quantity', '>', 0)
    }

    public function stockList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->select('products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity')
            ->orderBy('products.category_id', 'ASC')
            ->get();
        $data['txt'] = "Product List";

        $html1 = view('dashboard.admin.reports.stocks.product_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function stockListWithVal(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->select('products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity', 'product_stocks.sell_price', 'product_stocks.buy_price_with_tax')
            ->orderBy('products.category_id', 'ASC')
            ->get();
        $data['txt'] = "Product List with Price";
        $html1 = view('dashboard.admin.reports.stocks.product_list_val', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function currentStock(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->where('product_stocks.quantity', '>', 0)
            ->select('products.product_title', 'products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity')
            ->orderBy('products.category_id', 'ASC')
            ->get();

        $data['txt'] = "Current Stock";
        $html1 = view('dashboard.admin.reports.stocks.product_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function currentStockwithVal(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->where('product_stocks.quantity', '>', 0)
            ->select('products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity', 'product_stocks.sell_price', 'product_stocks.buy_price_with_tax')
            ->orderBy('products.category_id', 'ASC')
            ->get();

        $data['txt'] = "Current Stock with Price";
        $html1 = view('dashboard.admin.reports.stocks.product_list_val', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function stockOut(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->where('product_stocks.quantity', '<=', 0)
            ->select('products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity')
            ->orderBy('products.category_id', 'ASC')
            ->get();

        $data['txt'] = "Out of Stock";
        $html1 = view('dashboard.admin.reports.stocks.product_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function expiredIn(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->where('product_stocks.quantity', '>', 0)
            ->where('product_stocks.expired_date', '<=', $to_date . ' 23:59:59')
            ->select('products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity', 'product_stocks.expired_date')
            ->orderBy('product_stocks.expired_date', 'asc')
            ->get();

        $printdate = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
        $data['txt'] = "Expired By " . $printdate;
        $html1 = view('dashboard.admin.reports.stocks.expired_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function typeWise(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'products.manufacturer_id' => $request->manufacturer_id,
            'products.category_id' => $request->category_id,
            'products.brand_id' => $request->brand_id,
            'products.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['products'] = DB::table('products')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where($whr)
            ->where('product_stocks.quantity', '>', 0)
            ->where('product_stocks.product_type', '=', $request->product_type)
            ->select('products.product_title', 'suppliers.supplier_name', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color', 'product_stocks.quantity', 'product_stocks.product_type')
            ->orderBy('products.category_id', 'ASC')
            ->get();

        $data['txt'] = "Product Type Wise ($request->product_type) Current Stock";
        $html1 = view('dashboard.admin.reports.stocks.product_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }

    public function damagePdtRpt(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();

        $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
        $from_date1 = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('F j, Y');
        $to_date1 = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
        $data['msg'] = $from_date1 . " to " . $to_date1;
        $data['txt'] = "Damaged Products";

        $data['damagepdts'] = DB::table('damage_products')
            ->join('products', 'products.id', '=', 'damage_products.product_id')
            ->join('product_stocks', 'product_stocks.id', '=', 'damage_products.pdtstock_id')
            ->select('products.product_title', 'damage_products.*', 'product_stocks.barcode', 'product_stocks.batch_no', 'product_stocks.serial_no', 'product_stocks.size', 'product_stocks.color')
            ->whereBetween('damage_products.damage_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
            ->where(['damage_products.store_id' => $store_id])
            ->get();

        $html1 = view('dashboard.admin.reports.stocks.product_damaged', $data)->render();



        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product List', 'html1' => $html1]);
        }
    }
}
