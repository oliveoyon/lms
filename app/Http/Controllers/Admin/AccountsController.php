<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\OpeningBalances;
use App\Models\Admin\Purchase;
use App\Models\Admin\Sale;
use App\Models\Admin\Taxes;
use App\Models\Admin\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountsController extends Controller
{
    public function account_type_list()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accheads = DB::table('account_heads')->select('id', 'account_head')->where(['ach_status' => 1, 'store_id' => $store_id])->get();
        return view('dashboard.admin.accountManagement.accountTypes', ['accheads' => $accheads]);
    }

    public function addAccountType(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'account_name.required' => 'Account Type Name is Required',
            'account_name.unique' => 'Account Type Already Exists',
            'account_head_id.required' => 'Account Head is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'account_name' => [
                'required',
                Rule::unique('account_types')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
            'account_head_id' => 'required',
        ], $messages);

        if ($request->acccount_head_id == 1 or $request->acccount_head_id == 3) {
            $normal = 1;
        } else {
            $normal = -1;
        }

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $accounttype = new Account_Types();
            $accounttype->account_type_hash_id =  md5(uniqid(rand(), true));
            $accounttype->account_head_id = $request->account_head_id;
            $accounttype->account_name = $request->account_name;
            $accounttype->store_id = \Auth::guard('admin')->user()->store_id;
            $accounttype->is_money = 0;
            $accounttype->code = Account_Types::where(['account_head_id' => $request->account_head_id])->max('code') + 1;
            $accounttype->normal = $normal;
            $accounttype->acctype_status = 1;
            $query = $accounttype->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Account Type Details Added Successfully']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // GET ALL Account Type
    public function getAccountTypesList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accounttype = Account_Types::select('id', 'account_name', 'account_head_id', 'acctype_status')->where('store_id', $store_id)->get();


        return datatables()::of($accounttype)
            ->addIndexColumn(0)
            ->addColumn('account_head_id', function ($row) {
                $data = DB::table('account_heads')->select('account_head')->where('id', $row['account_head_id'])->first();
                return $data->account_head;
            })
            ->addColumn('status', function ($row) {
                if ($row['acctype_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editAccountTypeBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteAccountTypeBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Account Type DETAILS
    public function getAccountTypeDetails(Request $request)
    {
        $account_type_id = $request->account_type_id;
        $accountTypeDetails = Account_Types::find($account_type_id);
        return response()->json(['details' => $accountTypeDetails]);
    }


    //UPDATE Account Type DETAILS
    public function updateAccountTypeDetails(Request $request)
    {
        $account_type_id = $request->uid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'account_name.required' => 'Account Type Name is Required',
            'account_name.unique' => 'Account Type Name Already Exists',
            'account_head_id.required' => 'Account Head is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'account_name' => 'required|unique:account_types,account_name,' . $account_type_id,
            'account_head_id' => 'required',

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $accounttype = Account_Types::find($account_type_id);
            $accounttype->account_head_id = $request->account_head_id;
            $accounttype->account_name = $request->account_name;
            $accounttype->acctype_status = $request->acctype_status;
            $query = $accounttype->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Account Type Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Account Type RECORD
    public function deleteAccountType(Request $request)
    {
        $account_type_id = $request->account_type_id;
        $query = Account_Types::find($account_type_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Account Type has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }


    ######    Tax Management Starts    ######


    public function taxlist()
    {
        return view('dashboard.admin.accountManagement.taxes');
    }

    public function addTax(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'tax_name.required' => 'Tax Name is Required',
            'tax_name.unique' => 'Tax Name Already Exists',
            'tax_short_name.required' => 'Tax Short Name is Required',
            'tax_short_name.unique' => 'Tax Short Name Already Exists',
            'tax_value_percent.required' => 'Tax Percentage value is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'tax_name' => [
                'required',
                Rule::unique('taxes')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
            'tax_short_name' => [
                'required',
                Rule::unique('taxes')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
            'tax_value_percent' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $tax = new Taxes();
            $tax->tax_hash_id =  md5(uniqid(rand(), true));
            $tax->tax_name = $request->tax_name;
            $tax->tax_short_name = $request->tax_short_name;
            $tax->tax_value_percent = $request->tax_value_percent;
            $tax->store_id = \Auth::guard('admin')->user()->store_id;
            $tax->tax_status = 1;
            $query = $tax->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Tax Details Added Successfully']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // GET ALL Tax
    public function getTaxsList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $taxes = Taxes::where('store_id', $store_id)->get();
        //$Categorys = Edu_Category::all();
        return datatables()::of($taxes)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['tax_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editTaxBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteTaxBtn">Delete</button>
                        </div>';
            })

            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Tax DETAILS
    public function getTaxDetails(Request $request)
    {
        $tax_id = $request->tax_id;
        $taxDetails = Taxes::find($tax_id);
        return response()->json(['details' => $taxDetails]);
    }


    //UPDATE Tax DETAILS
    public function updateTaxDetails(Request $request)
    {
        $tax_id = $request->uid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'tax_name.required' => 'Tax Name is Required',
            'tax_name.unique' => 'Tax Name Already Exists',
            'tax_short_name.required' => 'Tax Short Name is Required',
            'tax_short_name.unique' => 'Tax Short Name Already Exists',
            'tax_value_percent.required' => 'Tax Percentage value is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'tax_name' => 'required|unique:taxes,tax_name,' . $tax_id,
            'tax_short_name' => 'required|unique:taxes,tax_short_name,' . $tax_id,
            'tax_value_percent' => 'required,' . $tax_id,

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $tax = Taxes::find($tax_id);
            $tax->tax_name = $request->tax_name;
            $tax->tax_short_name = $request->tax_short_name;
            $tax->tax_value_percent = $request->tax_value_percent;
            $tax->tax_status = $request->tax_status;
            $query = $tax->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Tax Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Tax RECORD
    public function deleteTax(Request $request)
    {
        $tax_id = $request->tax_id;
        $query = Taxes::find($tax_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Tax has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function openingBalance()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('opening_balances')->select('id')->where(['store_id' => $store_id])->get();
        session()->forget('purchaseReturn');
        $inv = 'OB-' . date('ymd') . '-' . count($invtotal) + 1;
        $data = ['store_id' => $store_id, 'inv' => $inv];
        return view('dashboard.admin.accountManagement.openingBalance', $data);
    }

    public function searchAccounts(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $category = $request->category;
        $htmlc = "<option value=''>Select an Accounts Name</option>";
        if ($category == 'supplier') {
            $suppliers = DB::table('suppliers')->select('supplier_name', 'parent_id')->where(['supplier_status' => 1, 'store_id' => $store_id])->get();
            foreach ($suppliers as $s) {
                $htmlc .= "<option value='$s->parent_id'>$s->supplier_name</option>";
            }
        } else {
            $customers = DB::table('customers')->select('customer_name', 'parent_id')->where(['customer_status' => 1, 'store_id' => $store_id])->get();
            foreach ($customers as $c) {
                $htmlc .= "<option value='$c->parent_id'>$c->customer_name</option>";
            }
        }
        return response()->json(['accounts' => $htmlc]);
    }

    public function obaction(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $allaccount = DB::table('account_types')->select('id', 'account_head_id', 'account_name', 'is_money', 'code')->where(['store_id' => $store_id, 'acctype_status' => 1])->get();
        $sales_revenue = $allaccount->where('account_name', 'Sales Revenue')->pluck('code')->first();
        $inventory = $allaccount->where('account_name', 'Inventory')->pluck('code')->first();
        $messages = [
            'category.required' => 'Category Name is Required',
            'account_id.required' => 'Account Name is Required',
            'amount.required' => 'Amount is Required',
            'obdate.required' => 'Date is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'category' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
            'obdate' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $ob = new OpeningBalances();
            $ob->ob_hash_id =  md5(uniqid(rand(), true));
            $ob->invoice_no = $request->invoice_no;
            $ob->category = $request->category;
            $ob->description = $request->description;
            $ob->account_id = $request->account_id;
            $ob->amount = $request->amount;
            $ob->entry_date = Carbon::createFromFormat('m/d/Y', $request->obdate)->format('Y-m-d');
            $ob->store_id = \Auth::guard('admin')->user()->store_id;
            $query = $ob->save();

            $save_data = [];
            if ($request->category == 'customer') {
                $save_data[] = [
                    'trns_id' => $request->invoice_no,
                    'account_head_id' => $request->account_id,
                    'description' => 'Opening Balance for Customer',
                    'amount' => $request->amount,
                    'direction' => 1,
                    'trns_date' => date('Y-m-d'),
                    'store_id' => $store_id
                ];
                $save_data[] = [
                    'trns_id' => $request->invoice_no,
                    'account_head_id' => $sales_revenue,
                    'description' => 'Opening Balance for Customer',
                    'amount' => $request->amount,
                    'direction' => -1,
                    'trns_date' => date('Y-m-d'),
                    'store_id' => $store_id
                ];
                Transactions::insert($save_data);

                $purchase = new Sale();
                $purchase->description = 'Opening Balance';
                $purchase->customer_id = $request->account_id;
                $purchase->invoice_no = $request->invoice_no;
                $purchase->trns_type = 1;
                $purchase->total = $request->amount;
                $purchase->due = $request->amount;
                $purchase->discount = 0;
                $purchase->paid = 0;
                $purchase->store_id = $store_id;
                $query = $purchase->save();
            } else {
                $save_data[] = [
                    'trns_id' => $request->invoice_no,
                    'account_head_id' => $inventory,
                    'description' => 'Opening Balance for supplier',
                    'amount' => $request->amount,
                    'direction' => 1,
                    'trns_date' => date('Y-m-d'),
                    'store_id' => $store_id
                ];
                $save_data[] = [
                    'trns_id' => $request->invoice_no,
                    'account_head_id' => $request->account_id,
                    'description' => 'Opening Balance for supplier',
                    'amount' => $request->amount,
                    'direction' => -1,
                    'trns_date' => date('Y-m-d'),
                    'store_id' => $store_id
                ];
                Transactions::insert($save_data);

                $supp = DB::table('suppliers')->where(['parent_id' => $request->account_id])->first();

                $purchase = new Purchase();
                $purchase->description = 'Opening Balance';
                $purchase->supplier_id = $supp->id;
                $purchase->invoice_no = $request->invoice_no;
                $purchase->trns_type = 1;
                $purchase->total = $request->amount;
                $purchase->due = $request->amount;
                $purchase->discount = 0;
                $purchase->paid = 0;
                $purchase->purchase_date = date('Y-m-d');
                $purchase->purchase_status = 1; // due, etc
                $purchase->store_id = $store_id;
                $query = $purchase->save();
            }

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Opening Balance Added Successfully']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function paymentSupplier()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accounts = DB::table('account_types')->select('account_name', 'code')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $suppliers = DB::table('suppliers')->select('id', 'supplier_name', 'supplier_phone')->where(['supplier_status' => 1, 'store_id' => $store_id])->get();
        $data = ['suppliers' => $suppliers, 'store_id' => $store_id, 'accounts' => $accounts];
        return view('dashboard.admin.accountManagement.paymentSuppliers', $data);
        //return view('demo', $data);
    }

    public function getSupPayment(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'invoice_no.required' => 'Invoice No is Required',
            'supplier_id.required' => 'Supplier Name is Required',
        ];
        if ($request->category == 'One') {
            $arr = ['invoice_no' => 'required'];
        } else {
            $arr = ['supplier_id' => 'required'];
        }
        $validator = \Validator::make($request->all(), $arr, $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            if ($request->category == 'One') {
                $invoice_no = 'PI-' . $request->invoice_no;
                $data['paymentLists'] = DB::select("SELECT s.supplier_name, p.invoice_no, p.description, sum(p.total) as total, sum(p.discount) as discount, sum(p.paid) as paid, (sum(p.total) - (sum(p.discount) + sum(p.paid))) as due FROM purchases p JOIN suppliers s ON p.supplier_id = s.id WHERE p.invoice_no = '$invoice_no' AND p.store_id = $store_id GROUP BY p.invoice_no HAVING due > 0;");
            }

            if ($request->category == 'Two') {

                if ($request->supplier_id == 'all') {
                    $data['paymentLists'] = DB::select("SELECT s.supplier_name, p.invoice_no, p.description, sum(p.total) as total, sum(p.discount) as discount, sum(p.paid) as paid, (sum(p.total) - (sum(p.discount) + sum(p.paid))) as due FROM purchases p JOIN suppliers s ON p.supplier_id = s.id WHERE p.store_id = 101 GROUP BY p.invoice_no HAVING due > 0;");
                } else {
                    $data['paymentLists'] = DB::select("SELECT s.supplier_name, p.invoice_no, p.description, sum(p.total) as total, sum(p.discount) as discount, sum(p.paid) as paid, (sum(p.total) - (sum(p.discount) + sum(p.paid))) as due FROM purchases p JOIN suppliers s ON p.supplier_id = s.id WHERE p.supplier_id = $request->supplier_id AND p.store_id = $store_id GROUP BY p.invoice_no HAVING due > 0;");
                }
            }

            $html1 = view('dashboard.admin.accountManagement.getListPaymentSupplier', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1,  'html1' => $html1]);
            }
        }
    }

    public function getSpplierPaymentDetails(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invoice_no = $request->invoice_no;
        $data = DB::select("SELECT `supplier_id`, `invoice_no`, `description`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM purchases WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no HAVING due > 0;");
        return response()->json(['details' => $data]);
    }

    public function updateSupplierPayment(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'payment.required' => 'Payment is Required',
            'accounts.required' => 'Account Name is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'payment' => 'required',
            'accounts' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $invoice_no = $request->sid;
            $data = DB::select("SELECT `supplier_id`, `invoice_no`, `description`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM purchases WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no HAVING due > 0;");
            $purchase = new Purchase();
            $purchase->description = $request->description;
            $purchase->supplier_id = $data[0]->supplier_id;
            $purchase->invoice_no = $data[0]->invoice_no;
            $purchase->trns_type = 1;
            $purchase->total = 0;
            $purchase->due = 0;
            $purchase->discount = 0;
            $purchase->paid = $request->payment;
            // $purchase->purchase_date = $purchase_date;
            $purchase->purchase_status = 1; // due, etc
            $purchase->store_id = $store_id;
            $query = $purchase->save();

            $suppliers = DB::table('suppliers')->where(['id' => $data[0]->supplier_id, 'store_id' => $store_id])->first();

            if ($data[0]->total == ($data[0]->paid + $data[0]->discount + $request->payment)) {
                Purchase::where('invoice_no', $data[0]->invoice_no)->update(['purchase_status' => 2]);
            }

            $save_data = [];
            $save_data[] = [
                'trns_id' => $data[0]->invoice_no,
                'account_head_id' => $suppliers->parent_id,
                'description' => 'Payment to Supplier for Invoice ' . $data[0]->invoice_no,
                'amount' => $request->payment,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $data[0]->invoice_no,
                'account_head_id' => $request->accounts,
                'description' => 'Payment to Supplier for Invoice ' . $data[0]->invoice_no,
                'amount' => $request->payment,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);


            $msg = 'Payment to Supplier for Invoice ' . $data[0]->invoice_no;
            $data1['datetime'] = $msg;
            $data1['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $data1['purchases'] = $data;
            $data1['suppliers'] = $suppliers;
            $data1['payment'] = $request->payment;

            $html2 = view('dashboard.admin.accountManagement.paymentInvoice', $data1)->render();

            if (!$data1) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html2' => $html2]);
            }
        }
    }

    public function receiveCustomer()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accounts = DB::table('account_types')->select('account_name', 'code')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $customers = DB::table('customers')->select('id', 'customer_name', 'customer_phone')->where(['customer_status' => 1, 'store_id' => $store_id])->get();
        $data = ['customers' => $customers, 'store_id' => $store_id, 'accounts' => $accounts];
        return view('dashboard.admin.accountManagement.receiveCustomer', $data);
        //return view('demo', $data);
    }

    public function getCusPayment(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'invoice_no.required' => 'Invoice No is Required',
            'customer_id.required' => 'Customer Name is Required',
        ];
        if ($request->category == 'One') {
            $arr = ['invoice_no' => 'required'];
        } else {
            $arr = ['customer_id' => 'required'];
        }
        $validator = \Validator::make($request->all(), $arr, $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            if ($request->category == 'One') {
                $invoice_no = 'SI-' . $request->invoice_no;
                $data['paymentLists'] = DB::select("SELECT c.customer_name, s.invoice_no, s.description, s.check_pending, sum(s.total) as total, sum(s.discount) as discount, sum(s.paid) as paid, (sum(s.total) - (sum(s.discount) + sum(s.paid))) as due FROM sales s JOIN customers c ON s.customer_id = c.parent_id WHERE s.invoice_no = '$invoice_no' AND s.store_id = $store_id GROUP BY s.invoice_no HAVING due > 0;");
            }

            if ($request->category == 'Two') {

                if ($request->customer_id == 'all') {
                    $data['paymentLists'] = DB::select("SELECT c.customer_name, s.invoice_no, s.description, s.check_pending, sum(s.total) as total, sum(s.discount) as discount, sum(s.paid) as paid, (sum(s.total) - (sum(s.discount) + sum(s.paid))) as due FROM sales s JOIN customers c ON s.customer_id = c.parent_id WHERE s.store_id = $store_id GROUP BY s.invoice_no HAVING due > 0;");
                } else {
                    $data['paymentLists'] = DB::select("SELECT c.customer_name, s.invoice_no, s.description, s.check_pending, sum(s.total) as total, sum(s.discount) as discount, sum(s.paid) as paid, (sum(s.total) - (sum(s.discount) + sum(s.paid))) as due FROM sales s JOIN customers c ON s.customer_id = c.parent_id WHERE s.customer_id = $request->customer_id AND s.store_id = $store_id GROUP BY s.invoice_no HAVING due > 0;");
                }
            }

            $html1 = view('dashboard.admin.accountManagement.getListPaymentCustomer', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1,  'html1' => $html1]);
            }
        }
    }

    public function getCustomerPaymentDetails(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invoice_no = $request->invoice_no;
        $data = DB::select("SELECT `customer_id`, `invoice_no`, `description`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no HAVING due > 0;");
        return response()->json(['details' => $data]);
    }

    public function updateCustomerPayment(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'payment.required' => 'Payment is Required',
            'accounts.required' => 'Account Name is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'payment' => 'required',
            'accounts' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $invoice_no = $request->sid;
            $data = DB::select("SELECT `customer_id`, `invoice_no`, `description`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no HAVING due > 0;");

            $sales = new Sale();
            $sales->description = $request->description;
            $sales->customer_id = $data[0]->customer_id;
            $sales->invoice_no = $data[0]->invoice_no;
            $sales->trns_type = $request->accounts;
            $sales->total = 0;
            $sales->due = 0;
            $sales->discount = 0;
            $sales->paid = $request->payment;
            // $purchase->purchase_date = $purchase_date;
            $sales->sale_status = 1; // due, etc
            $sales->store_id = $store_id;
            $query = $sales->save();

            $customers = DB::table('customers')->where(['parent_id' => $data[0]->customer_id, 'store_id' => $store_id])->first();

            if ($data[0]->total == ($data[0]->paid + $data[0]->discount + $request->payment)) {
                Purchase::where('invoice_no', $data[0]->invoice_no)->update(['purchase_status' => 2]);
            }

            $save_data = [];
            $save_data[] = [
                'trns_id' => $data[0]->invoice_no,
                'account_head_id' => $request->accounts,
                'description' => 'Received from Customer for Invoice ' . $data[0]->invoice_no,
                'amount' => $request->payment,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $data[0]->invoice_no,
                'account_head_id' => $data[0]->customer_id,
                'description' => 'Received from Customer for Invoice ' . $data[0]->invoice_no,
                'amount' => $request->payment,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);


            $msg = 'Received from Customer for Invoice ' . $data[0]->invoice_no;
            $data1['datetime'] = $msg;
            $data1['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $data1['sales'] = DB::select("SELECT `customer_id`, `invoice_no`, `description`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no;");
            $data1['customers'] = $customers;
            $data1['payment'] = $request->payment;

            $html2 = view('dashboard.admin.accountManagement.receiveInvoice', $data1)->render();

            if (!$data1) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html2' => $html2]);
            }
        }
    }

    public function chequeClearance()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accounts = DB::table('account_types')->select('account_name', 'code')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $customers = DB::table('customers')->select('id', 'customer_name', 'customer_phone')->where(['customer_status' => 1, 'store_id' => $store_id])->get();
        $data = ['customers' => $customers, 'store_id' => $store_id, 'accounts' => $accounts];
        return view('dashboard.admin.accountManagement.receiveCustomerChq', $data);
    }

    public function getCustomerPaymentDetailsChq(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invoice_no = $request->invoice_no;
        $data = DB::select("SELECT `customer_id`, `invoice_no`, `description`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id AND check_pending = 1 GROUP BY invoice_no HAVING due > 0;");
        return response()->json(['details' => $data]);
    }

    public function updateCustomerPaymentChq(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'payment.required' => 'Payment is Required',
            'accounts.required' => 'Account Name is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'payment' => 'required',
            'accounts' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $invoice_no = $request->sid;
            $data = DB::select("SELECT `customer_id`, `invoice_no`, `description`, `check_pending`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id AND check_pending = 1 GROUP BY invoice_no HAVING due > 0;");
            $sales = new Sale();
            $sales->description = $request->description;
            $sales->customer_id = $data[0]->customer_id;
            $sales->invoice_no = $data[0]->invoice_no;
            $sales->trns_type = $request->accounts;
            $sales->total = 0;
            $sales->due = 0;
            $sales->discount = 0;
            $sales->paid = $request->payment;
            // $purchase->purchase_date = $purchase_date;
            $sales->sale_status = 1; // due, etc
            $sales->store_id = $store_id;
            $query = $sales->save();

            $customers = DB::table('customers')->where(['parent_id' => $data[0]->customer_id, 'store_id' => $store_id])->first();
            $chk = DB::select("SELECT `customer_id`, `invoice_no`, `description`, `check_pending`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no;");

            if ($chk[0]->due == 0) {
                DB::table('sales')->where(['invoice_no' => $data[0]->invoice_no])->update(array('sale_status' => 2, 'check_pending' => 0));
            }

            $save_data = [];
            $save_data[] = [
                'trns_id' => $data[0]->invoice_no,
                'account_head_id' => $request->accounts,
                'description' => 'Received from Customer for Invoice ' . $data[0]->invoice_no,
                'amount' => $request->payment,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $data[0]->invoice_no,
                'account_head_id' => $data[0]->customer_id,
                'description' => 'Received from Customer for Invoice ' . $data[0]->invoice_no,
                'amount' => $request->payment,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);


            $msg = 'Received from Customer for Invoice ' . $data[0]->invoice_no;
            $data1['datetime'] = $msg;
            $data1['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $data1['sales'] = $chk;
            $data1['customers'] = $customers;
            $data1['payment'] = $request->payment;

            $html2 = view('dashboard.admin.accountManagement.receiveInvoice', $data1)->render();

            if (!$data1) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => $msg, 'html2' => $html2]);
            }
        }
    }

    public function getCusPaymentChq(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'invoice_no.required' => 'Invoice No is Required',
            'customer_id.required' => 'Customer Name is Required',
        ];
        if ($request->category == 'One') {
            $arr = ['invoice_no' => 'required'];
        } else {
            $arr = ['customer_id' => 'required'];
        }
        $validator = \Validator::make($request->all(), $arr, $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            if ($request->category == 'One') {
                $invoice_no = 'SI-' . $request->invoice_no;
                $data['paymentLists'] = DB::select("SELECT `customer_id`, `invoice_no`, `description`, `check_pending`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE invoice_no = '$invoice_no' AND store_id = $store_id GROUP BY invoice_no HAVING due > 0;");
            }

            if ($request->category == 'Two') {

                if ($request->customer_id == 'all') {
                    $data['paymentLists'] = DB::select("SELECT `customer_id`, `invoice_no`, `description`, `check_pending`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE store_id = $store_id GROUP BY invoice_no HAVING due > 0;");
                } else {
                    $data['paymentLists'] = DB::select("SELECT `customer_id`, `invoice_no`, `description`, `check_pending`, sum(total) as total, sum(discount) as discount, sum(paid) as paid, (sum(total) - (sum(discount) + sum(paid))) as due FROM sales WHERE supplier_id = $request->supplier_id AND store_id = $store_id GROUP BY invoice_no HAVING due > 0;");
                }
            }

            $html1 = view('dashboard.admin.accountManagement.getListPaymentCustomer', $data)->render();

            if (!$data) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1,  'html1' => $html1]);
            }
        }
    }
}
