<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountsReportController extends Controller
{
    public function index()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['suppliers'] = DB::table('suppliers')->where(['store_id' => $store_id])->get();
        return view('dashboard.admin.reports.accounts.homereports', $data);
    }

    public function purchaseReports()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['suppliers'] = DB::table('suppliers')->select('id', 'parent_id', 'supplier_name')->where(['store_id' => $store_id])->get();
        return view('dashboard.admin.reports.accounts.purchaseReports', $data);
    }

    public function suppliersList()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['suppliers'] = DB::table('suppliers')->where(['store_id' => $store_id])->get();
        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML(view('dashboard.admin.reports.accounts.supplier', $data));
        // $mpdf->Output();
        // return view('dashboard.admin.reports.accounts.suppliers_list', $data);

        $html1 = view('dashboard.admin.reports.accounts.suppliers_list', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Suppliers List', 'html1' => $html1]);
        }
    }

    public function payable()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['suppliers'] = DB::select("select tr.account_head_id, sp.supplier_address, sp.supplier_phone, ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join suppliers sp on ac.code = sp.parent_id WHERE tr.account_head_id LIKE '2%' AND ac.acc_type = 'supplier' AND tr.store_id = $store_id group by ac.account_name order by tr.account_head_id, ac.account_name");

        $html1 = view('dashboard.admin.reports.accounts.payable', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Payable Suppliers List', 'html1' => $html1]);
        }
    }

    public function test()
    {
        $data = DB::select("select tr.account_head_id, sp.supplier_address, sp.supplier_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join suppliers sp on ac.code = sp.parent_id WHERE ((tr.account_head_id LIKE '2%') AND (tr.trns_date BETWEEN '2023-02-01' AND '2023-02-09') AND tr.store_id = 101 ) group by account_name order by tr.account_head_id, ac.account_name;");

        dd($data);
    }

    public function datewisepayable(Request $request)
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
            $data['suppliers'] = DB::select("select tr.account_head_id, sp.supplier_address, sp.supplier_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join suppliers sp on ac.code = sp.parent_id WHERE (tr.account_head_id LIKE '$request->supplier_id' AND (tr.trns_date BETWEEN '$from_date' AND '$to_date') AND tr.store_id = $store_id ) group by account_name order by tr.account_head_id, ac.account_name;");

            $html1 = view('dashboard.admin.reports.accounts.payables', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }

    public function datewispurchase(Request $request)
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

            if ($request->supplier_id == 'all') {
                $data['products'] = DB::table('product_stocks')
                    ->join('products', 'products.id', '=', 'product_stocks.product_id')
                    ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
                    ->where('product_stocks.invoice_no', 'like', 'PI-%')
                    ->where('product_stocks.store_id', '=', $store_id)
                    ->whereBetween('product_stocks.purchase_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('product_stocks.*', 'products.product_title', 'suppliers.supplier_name')
                    ->get();
            } else {
                $data['products'] = DB::table('product_stocks')
                    ->join('products', 'products.id', '=', 'product_stocks.product_id')
                    ->join('suppliers', 'suppliers.id', '=', 'product_stocks.supplier_id')
                    ->where('product_stocks.invoice_no', 'like', 'PI-%')
                    ->where('product_stocks.store_id', '=', $store_id)
                    ->where('product_stocks.supplier_id', '=', $request->supplier_id)
                    ->whereBetween('product_stocks.purchase_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('product_stocks.*', 'products.product_title', 'suppliers.supplier_name')
                    ->get();
            }
            $file_name = 'purchase_product_' . $store_id . '.pdf';
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML(view('dashboard.admin.reports.accounts.purchase_products', $data));
            $mpdf->Output($file_name, 'F');
            $fname = '<button class="btn btn-success" onclick="myfunctionName(\'http://127.0.0.1:8000/' . $file_name . '\')">Print</button>';


            //return view('dashboard.admin.reports.accounts.purchase_products', $data);


            $html1 = view('dashboard.admin.reports.accounts.purchase_products', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1, 'fname' => $fname]);
            }
        }
    }

    public function purchaseinvoice(Request $request)
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
            $msg = "Invoice wise purchase as on " . $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            // $data['suppliers'] = DB::select("select tr.account_head_id, sp.supplier_address, sp.supplier_phone, GROUP_CONCAT(tr.trns_id SEPARATOR ', ') AS invoices , GROUP_CONCAT((tr.amount * tr.direction * ac.normal) SEPARATOR ', ') AS amounts , ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join suppliers sp on ac.code = sp.parent_id WHERE (tr.account_head_id LIKE '$request->supplier_id' AND (tr.trns_date BETWEEN '$from_date' AND '$to_date') AND tr.store_id = 101 ) group by account_name order by tr.account_head_id, ac.account_name;");

            if ($request->supplier_id == 'all') {

                $data['invoices'] = DB::table('purchases')
                    ->join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
                    ->select('purchases.id', 'suppliers.supplier_name', 'purchases.invoice_no', DB::raw('SUM(purchases.total) as total'), DB::raw('SUM(purchases.due) as due'), DB::raw('SUM(purchases.discount) as discount'), DB::raw('SUM(paid) as paid'))
                    ->where('purchases.store_id', '=', $store_id)
                    ->whereBetween('purchases.purchase_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->groupBy('purchases.invoice_no')
                    ->get();
            } else {
                $data['invoices'] = DB::table('purchases')
                    ->join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
                    ->select('purchases.id', 'suppliers.supplier_name', 'purchases.invoice_no', DB::raw('SUM(purchases.total) as total'), DB::raw('SUM(purchases.due) as due'), DB::raw('SUM(purchases.discount) as discount'), DB::raw('SUM(paid) as paid'))
                    ->where('purchases.store_id', '=', $store_id)
                    ->where('purchases.supplier_id', '=', $request->supplier_id)
                    ->whereBetween('purchases.purchase_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->groupBy('purchases.invoice_no')
                    ->get();
            }
            // $file_name = 'purchase_product_' . $store_id . '.pdf';
            // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            // $mpdf->WriteHTML(view('dashboard.admin.reports.accounts.purchase_invoice', $data));
            // $mpdf->Output($file_name, 'F');
            // $fname = '<button class="btn btn-success" onclick="myfunctionName(\'http://127.0.0.1:8000/' . $file_name . '\')">Print</button>';


            //return view('dashboard.admin.reports.accounts.purchase_products', $data);


            $html1 = view('dashboard.admin.reports.accounts.purchase_invoice', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }

    public static function getPurchaseDetail($inv)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $detail = DB::table('product_stocks')
            ->join('products', 'products.id', '=', 'product_stocks.product_id')
            ->where('product_stocks.invoice_no', '=', $inv)
            ->where('product_stocks.store_id', '=', $store_id)
            ->select('product_stocks.*', 'products.product_title')
            ->get();
        return $detail;
    }

    public function purchasereturn(Request $request)
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

            if ($request->supplier_id == 'all') {
                $data['products'] = DB::table('purchase_returns')
                    ->join('products', 'products.id', '=', 'purchase_returns.product_id')
                    ->join('suppliers', 'suppliers.id', '=', 'purchase_returns.supplier_id')
                    ->join('product_stocks', 'product_stocks.id', '=', 'purchase_returns.pdtstock_id')
                    ->where('purchase_returns.store_id', '=', $store_id)
                    ->whereBetween('purchase_returns.return_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('product_stocks.*', 'products.product_title', 'suppliers.supplier_name', 'purchase_returns.*')
                    ->get();
            } else {
                $data['products'] = DB::table('purchase_returns')
                    ->join('products', 'products.id', '=', 'purchase_returns.product_id')
                    ->join('suppliers', 'suppliers.id', '=', 'purchase_returns.supplier_id')
                    ->join('product_stocks', 'product_stocks.id', '=', 'purchase_returns.pdtstock_id')
                    ->where('purchase_returns.store_id', '=', $store_id)
                    ->where('purchase_returns.supplier_id', '=', $request->supplier_id)
                    ->whereBetween('purchase_returns.return_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->select('product_stocks.*', 'products.product_title', 'suppliers.supplier_name', 'purchase_returns.*')
                    ->get();
            }
            $file_name = 'return_product_' . $store_id . '.pdf';
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML(view('dashboard.admin.reports.accounts.return_products', $data));
            $mpdf->Output($file_name, 'F');
            $fname = '<button class="btn btn-success" onclick="myfunctionName(\'http://127.0.0.1:8000/' . $file_name . '\')">Print</button>';


            $html1 = view('dashboard.admin.reports.accounts.return_products', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1, 'fname' => $fname]);
            }
        }
    }

    public function accountsReport()
    {
        $store_id = Auth::guard('admin')->user()->store_id;
        $accheads = DB::table('account_heads')->select('id', 'account_head')->get();
        $exps = DB::table('account_types')->select('code', 'account_name')->where(['account_head_id' => 3, 'store_id' => $store_id])->get();
        $chartOfAccounts = DB::table('account_types')
            ->join('account_heads', 'account_heads.id', '=', 'account_types.account_head_id')
            ->where('account_types.store_id', '=', $store_id)
            ->where('account_types.is_money', '=', 1)
            ->orderBy('account_types.account_head_id')
            ->select('account_types.*', 'account_heads.account_head')
            ->get();
        $data = ['chartOfAccounts' => $chartOfAccounts, 'accheads' => $accheads, 'expenditures' => $exps];
        return view('dashboard.admin.reports.accounts.accountsReport', $data);
    }

    public function getAccounts(Request $request)
    {
        $store_id = Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $whr = [
            'account_types.account_head_id' => $request->acc_head_id,
            'account_types.store_id' => $store_id,
        ];
        $whr = array_filter($whr);

        $data['accounts'] = DB::table('account_types')
            ->join('account_heads', 'account_heads.id', '=', 'account_types.account_head_id')
            ->where($whr)
            ->orderBy('account_types.account_head_id')
            ->select('account_types.*', 'account_heads.account_head')
            ->get();

        $data['txt'] = "Chart of Accounts";

        $html1 = view('dashboard.admin.reports.accounts.chart_of_account', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Chart of Accounts', 'html1' => $html1]);
        }
    }

    public function expenditureReport(Request $request)
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
            $msg = "Expenditure as on " . $from_date1 . " to " . $to_date1;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $whr = [
                'expenditures.acc_head_id' => $request->acc_head_id,
                'expenditures.store_id' => $store_id
            ];
            $whr = array_filter($whr);



            $data['info'] = DB::select("SELECT (tr.amount * tr.direction * ats.normal) AS Exp, tr.trns_id, tr.trns_date, tr.description, ats.account_name, exps.description as expdesc FROM transactions tr LEFT JOIN account_types ats ON ats.code = tr.account_head_id LEFT JOIN expenditures exps ON tr.trns_id = exps.invoice_no WHERE ats.account_head_id = 3 AND tr.store_id = $store_id AND tr.trns_date BETWEEN '$from_date' AND '$to_date';");
            // dd($data['info']);

            $html1 = view('dashboard.admin.accountManagement.expenditureInvoice', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }

    public function balancesheet()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['balancesheet'] = DB::select("SELECT ah.account_head, SUM(tr.amount * tr.direction * ac.normal) AS balance FROM transactions tr LEFT JOIN account_types ac ON tr.account_head_id = ac.code JOIN account_heads ah ON ac.account_head_id = ah.id WHERE tr.store_id = $store_id GROUP BY ah.account_head ORDER BY ah.account_head;");
        $html1 = view('dashboard.admin.reports.accounts.balancesheet', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Balance Sheet', 'html1' => $html1]);
        }
    }

    public function trialbalance()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['balancesheet'] = DB::select("SELECT account_heads.account_head AS account_type, account_types.account_name AS account_name, SUM(CASE WHEN transactions.direction = 1 THEN transactions.amount * account_types.normal ELSE 0 END) AS debit_total, SUM(CASE WHEN transactions.direction = -1 THEN transactions.amount * account_types.normal ELSE 0 END) AS credit_total, SUM(transactions.amount * transactions.direction * account_types.normal) AS balance FROM transactions JOIN account_types ON account_types.code = transactions.account_head_id JOIN account_heads ON account_heads.id = account_types.account_head_id WHERE transactions.store_id = $store_id GROUP BY account_heads.account_head, account_types.account_name ORDER BY account_heads.account_head, account_types.account_name;");
        $html1 = view('dashboard.admin.reports.accounts.trialbalance', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Trial Balance', 'html1' => $html1]);
        }
    }

    public function cashflow()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $data['cashflow'] = DB::select("SELECT 
        CASE 
          WHEN account_heads.account_head = 'Assets' THEN 'Operating Activities' 
          WHEN account_types.account_head_id = 4 THEN 'Operating Activities' 
          WHEN account_heads.account_head = 'Liabilities' THEN 'Financing Activities'
          WHEN account_types.account_head_id = 5 THEN 'Financing Activities'
          ELSE 'Investing Activities' 
        END AS category, 
        SUM(CASE 
              WHEN account_types.normal = 1 AND account_types.account_head_id = 4 THEN tr.amount * tr.direction * account_types.normal 
              WHEN account_types.normal = -1 AND account_types.account_head_id = 5 THEN tr.amount * tr.direction * account_types.normal 
              ELSE 0 
            END) AS cash_inflow,
        SUM(CASE 
              WHEN account_types.normal = -1 AND account_types.account_head_id = 4 THEN tr.amount * tr.direction * account_types.normal 
              WHEN account_types.normal = 1 AND account_types.account_head_id = 5 THEN tr.amount * tr.direction * account_types.normal 
              ELSE 0 
            END) AS cash_outflow
      FROM 
        transactions tr
        JOIN account_types ON account_types.code = tr.account_head_id
        JOIN account_heads ON account_heads.id = account_types.account_head_id
      WHERE 
        account_heads.account_head IN ('Assets', 'Liabilities', 'Equity') AND tr.store_id = $store_id
      GROUP BY 
        category
      ORDER BY 
        category;");
        $html1 = view('dashboard.admin.reports.accounts.cashflow', $data)->render();
        if (!$data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Cash Flow Statement', 'html1' => $html1]);
        }
    }

    public function incomestatement(Request $request)
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
            $msg = "Income Statement " . $from_date1 . " to " . $to_date1;
            $data['msgs'] = $msg;
            $data['datetime'] = $msg;
            $data['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $data['incomestats'] = DB::select("select ah.account_head, tr.account_head_id, tr.description, ac.account_name, (tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code join account_heads ah on ac.account_head_id = ah.id WHERE tr.store_id = $store_id AND (ah.account_head = 'Expenses' OR ah.account_head = 'Revenue') AND (tr.trns_date >= '$from_date' AND tr.trns_date <= '$to_date') ORDER BY ah.account_head;");
            // dd($to_date);
            // dd($data['incomestats']);
            $html1 = view('dashboard.admin.reports.accounts.incomestats', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html1' => $html1]);
            }
        }
    }




    public static function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array(
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return ucfirst(implode(' ', $words));
    }
}
