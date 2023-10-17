<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index()
    {
        $store_id = Auth::guard('admin')->user()->store_id;
        $customers = DB::table('customers')->select('parent_id', 'customer_name')->where(['store_id' => $store_id])->get();
        $data = ['customers' => $customers];
        return view('dashboard.admin.reports.sales.salesreport', $data);
    }

    public function showCustomers()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['customers'] = DB::table('customers')->where(['store_id' => $store_id])->get();

        $html1 = view('dashboard.admin.reports.sales.customer_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Customers List', 'html1' => $html1]);
        }
    }

    public function showReceivable()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['customers'] = DB::select("select tr.account_head_id, sp.customer_address, sp.customer_phone, ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join customers sp on ac.code = sp.parent_id WHERE tr.account_head_id LIKE '1%' AND ac.acc_type = 'customer' AND tr.store_id = $store_id group by ac.account_name order by tr.account_head_id, ac.account_name");

        $html1 = view('dashboard.admin.reports.sales.receivable', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Payable Customer List', 'html1' => $html1]);
        }
    }

    public static function getcustomername($id)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data = DB::table('customers')->where(['parent_id' => $id, 'store_id' => $store_id])->first();
        return $data->customer_name;
    }

    public function datewisreceivale(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'from_date.required' => 'From Date is Required',
            'to_date.required' => 'To Date is Required',
            'to_date.after' => 'To Date must be greater than From Date',
        ];
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'date|after:from_date'
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
            $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
            $from_date1 = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('F j, Y');
            $to_date1 = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
            $msg = $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            if ($request->customer_id == 'all') {
                $data['customers'] = DB::select("select tr.account_head_id, sp.customer_address, sp.customer_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join customers sp on ac.code = sp.parent_id WHERE ( (tr.trns_date BETWEEN '$from_date' AND '$to_date') AND tr.store_id = $store_id AND ac.acc_type = 'customer' ) group by account_name order by tr.account_head_id, ac.account_name;");
            } else {
                $data['customers'] = DB::select("select tr.account_head_id, sp.customer_address, sp.customer_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join customers sp on ac.code = sp.parent_id WHERE (tr.account_head_id LIKE '$request->customer_id' AND (tr.trns_date BETWEEN '$from_date' AND '$to_date') AND tr.store_id = $store_id AND ac.acc_type = 'customer') group by account_name order by tr.account_head_id, ac.account_name;");
            }


            $html1 = view('dashboard.admin.reports.sales.receivables', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }


    public function datewisesale(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'from_date.required' => 'From Date is Required',
            'to_date.required' => 'To Date is Required',
            'to_date.after_or_equal' => 'To Date must be equal or greater than From Date',
        ];
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'date|after_or_equal:from_date'
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
            $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
            $from_date1 = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('F j, Y');
            $to_date1 = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
            $msg = $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            // $data['suppliers'] = DB::select("select tr.account_head_id, sp.supplier_address, sp.supplier_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join suppliers sp on ac.code = sp.parent_id WHERE (tr.account_head_id LIKE '$request->supplier_id' AND (tr.trns_date BETWEEN '$from_date' AND '$to_date') AND tr.store_id = 101 ) group by account_name order by tr.account_head_id, ac.account_name;");

            if ($request->customer_id == 'all') {
                $data['products'] = DB::table('sale_products')
                    ->join('products', 'products.id', '=', 'sale_products.product_id')
                    ->join('product_stocks', 'product_stocks.id', '=', 'sale_products.pdtstock_id')
                    ->join('customers', 'customers.parent_id', '=', 'sale_products.customer_id')
                    ->where('sale_products.store_id', '=', $store_id)
                    ->whereBetween('sale_products.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('sale_products.*', 'products.product_title', 'customers.customer_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color')
                    ->get();
            } else {
                $data['products'] = DB::table('sale_products')
                    ->join('products', 'products.id', '=', 'sale_products.product_id')
                    ->join('product_stocks', 'product_stocks.id', '=', 'sale_products.pdtstock_id')
                    ->join('customers', 'customers.parent_id', '=', 'sale_products.customer_id')
                    ->where('sale_products.store_id', '=', $store_id)
                    ->where('sale_products.customer_id', '=', $request->customer_id)
                    ->whereBetween('sale_products.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('sale_products.*', 'products.product_title', 'customers.customer_name', 'product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'product_stocks.size', 'product_stocks.color')
                    ->get();
            }


            $html1 = view('dashboard.admin.reports.sales.sale_products', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }

    public function salesinvoice(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'from_date.required' => 'From Date is Required',
            'to_date.required' => 'To Date is Required',
            'to_date.after_or_equal' => 'To Date must be equal or greater than From Date',
        ];
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'date|after_or_equal:from_date'
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
            $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
            $from_date1 = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('F j, Y');
            $to_date1 = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
            $msg = "Invoice wise sales as on " . $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();

            if ($request->customer_id == 'all') {

                $data['invoices'] = DB::table('sales')
                    ->join('customers', 'customers.parent_id', '=', 'sales.customer_id')
                    ->select('sales.id', 'customers.customer_name', 'sales.invoice_no', DB::raw('SUM(sales.total) as total'), DB::raw('SUM(sales.due) as due'), DB::raw('SUM(sales.discount) as discount'), DB::raw('SUM(sales.paid) as paid'))
                    ->where('sales.store_id', '=', $store_id)
                    ->whereBetween('sales.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->groupBy('sales.invoice_no')
                    ->get();
            } else {
                $data['invoices'] = DB::table('sales')
                    ->join('customers', 'customers.parent_id', '=', 'sales.customer_id')
                    ->select('sales.id', 'customers.customer_name', 'sales.invoice_no', DB::raw('SUM(sales.total) as total'), DB::raw('SUM(sales.due) as due'), DB::raw('SUM(sales.discount) as discount'), DB::raw('SUM(sales.paid) as paid'))
                    ->where('sales.store_id', '=', $store_id)
                    ->where('sales.customer_id', '=', $request->customer_id)
                    ->whereBetween('sales.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->groupBy('sales.invoice_no')
                    ->get();
            }

            $html1 = view('dashboard.admin.reports.sales.sales_invoice', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }

    public function salesinvoice1(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'from_date.required' => 'From Date is Required',
            'to_date.required' => 'To Date is Required',
            'to_date.after_or_equal' => 'To Date must be equal or greater than From Date',
        ];
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'date|after_or_equal:from_date'
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
            $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
            $from_date1 = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('F j, Y');
            $to_date1 = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
            $msg = "Invoice wise sales as on " . $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();

            if ($request->customer_id == 'all') {

                $data['invoices'] = DB::table('sales')
                    ->join('customers', 'customers.parent_id', '=', 'sales.customer_id')
                    ->select('sales.id', 'customers.customer_name', 'sales.invoice_no', DB::raw('SUM(sales.total) as total'), DB::raw('SUM(sales.due) as due'), DB::raw('SUM(sales.discount) as discount'), DB::raw('SUM(sales.paid) as paid'))
                    ->where('sales.store_id', '=', $store_id)
                    ->whereBetween('sales.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->groupBy('sales.invoice_no')
                    ->get();
            } else {
                $data['invoices'] = DB::table('sales')
                    ->join('customers', 'customers.parent_id', '=', 'sales.customer_id')
                    ->select('sales.id', 'customers.customer_name', 'sales.invoice_no', DB::raw('SUM(sales.total) as total'), DB::raw('SUM(sales.due) as due'), DB::raw('SUM(sales.discount) as discount'), DB::raw('SUM(sales.paid) as paid'))
                    ->where('sales.store_id', '=', $store_id)
                    ->where('sales.customer_id', '=', $request->customer_id)
                    ->whereBetween('sales.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->groupBy('sales.invoice_no')
                    ->get();
            }

            $html1 = view('dashboard.admin.reports.sales.sales_invoice1', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }

    public function getInv(Request $request)
    {
        $invoice_no = $request->invoice_no;
        $store_id = \Auth::guard('admin')->user()->store_id;
        $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $datas['sales'] = DB::table('sales')->where(['invoice_no' => $invoice_no, 'store_id' => $store_id])->first();

        $datas['salepdts'] = DB::table('sale_products')
            ->join('products', 'products.id', '=', 'sale_products.product_id')
            ->select('products.product_title', 'sale_products.*')
            ->where(['sale_products.invoice_no' => $invoice_no, 'sale_products.store_id' => $store_id])
            ->get();

        $datas['customer'] = DB::table('customers')->where(['parent_id' => $datas['sales']->customer_id, 'store_id' => $store_id])->first();

        $html1 = view('dashboard.admin.reports.accounts.sample_report.invoice', $datas)->render();
        if (!$datas) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Sales Invoice', 'html1' => $html1]);
        }
    }

    public function getChallan(Request $request)
    {
        $invoice_no = $request->invoice_no;
        $store_id = \Auth::guard('admin')->user()->store_id;
        $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $datas['sales'] = DB::table('sales')->where(['invoice_no' => $invoice_no, 'store_id' => $store_id])->first();

        $datas['salepdts'] = DB::table('sale_products')
            ->join('products', 'products.id', '=', 'sale_products.product_id')
            ->select('products.product_title', 'sale_products.*')
            ->where(['sale_products.invoice_no' => $invoice_no, 'sale_products.store_id' => $store_id])
            ->get();

        $datas['customer'] = DB::table('customers')->where(['parent_id' => $datas['sales']->customer_id, 'store_id' => $store_id])->first();

        $html1 = view('dashboard.admin.reports.accounts.sample_report.challan', $datas)->render();
        if (!$datas) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Challan', 'html1' => $html1]);
        }
    }

    public static function getSaleDetail($inv)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $detail = DB::table('sale_products')
            ->join('products', 'products.id', '=', 'sale_products.product_id')
            ->join('product_stocks', 'product_stocks.id', '=', 'sale_products.pdtstock_id')
            ->where('sale_products.invoice_no', '=', $inv)
            ->where('sale_products.store_id', '=', $store_id)
            ->select('product_stocks.barcode', 'product_stocks.serial_no', 'product_stocks.batch_no', 'products.product_title', 'sale_products.*')
            ->get();
        return $detail;
    }

    public function salesreturn(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'from_date.required' => 'From Date is Required',
            'to_date.required' => 'To Date is Required',
            'to_date.after_or_equal' => 'To Date must be equal or greater than From Date',
        ];
        $validator = \Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'date|after_or_equal:from_date'
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $from_date = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');
            $to_date = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');
            $from_date1 = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('F j, Y');
            $to_date1 = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('F j, Y');
            $msg = $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            // $data['suppliers'] = DB::select("select tr.account_head_id, sp.supplier_address, sp.supplier_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join suppliers sp on ac.code = sp.parent_id WHERE (tr.account_head_id LIKE '$request->supplier_id' AND (tr.trns_date BETWEEN '$from_date' AND '$to_date') AND tr.store_id = 101 ) group by account_name order by tr.account_head_id, ac.account_name;");

            if ($request->customer_id == 'all') {
                $data['products'] = DB::table('sale_returns')
                    ->join('products', 'products.id', '=', 'sale_returns.product_id')
                    ->join('customers', 'customers.parent_id', '=', 'sale_returns.customer_id')
                    ->join('product_stocks', 'product_stocks.id', '=', 'sale_returns.pdtstock_id')
                    ->where('sale_returns.store_id', '=', $store_id)
                    ->whereBetween('sale_returns.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('product_stocks.*', 'products.product_title', 'customers.customer_name', 'sale_returns.*')
                    ->get();
            } else {
                $data['products'] = DB::table('sale_returns')
                    ->join('products', 'products.id', '=', 'sale_returns.product_id')
                    ->join('customers', 'customers.parent_id', '=', 'sale_returns.customer_id')
                    ->join('product_stocks', 'product_stocks.id', '=', 'sale_returns.pdtstock_id')
                    ->where('sale_returns.store_id', '=', $store_id)
                    ->whereBetween('sale_returns.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('product_stocks.*', 'products.product_title', 'customers.customer_name', 'sale_returns.*')
                    ->get();
            }



            $html1 = view('dashboard.admin.reports.sales.return_products', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }
}
