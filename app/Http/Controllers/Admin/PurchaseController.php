<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\Category;
use App\Models\Admin\Location;
use App\Models\Admin\Product;
use App\Models\Admin\productStock;
use App\Models\Admin\PurchaseReturns;
use App\Models\Admin\Supplier;
use App\Models\Admin\Transactions;
use App\Models\Admin\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;
use Datatables;

class PurchaseController extends Controller
{

    ######    Supplier Management Starts    ######

    public function purchaseReturn()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('purchase_returns')->select('id')->where(['store_id' => $store_id])->get();
        session()->forget('purchaseReturn');
        $inv = 'PR-' . date('ymd') . '-' . count($invtotal) + 1;
        $suppliers = DB::table('suppliers')->select('id', 'supplier_name')->where(['supplier_status' => 1, 'store_id' => $store_id])->get();
        $data = ['suppliers' => $suppliers, 'store_id' => $store_id, 'inv' => $inv];
        return view('dashboard.admin.productManagement.purchaseReturn', $data);
    }

    public function searchProducts(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $products = productStock::select('products.product_title', 'product_stocks.*')
            ->leftJoin('products', 'product_stocks.product_id', '=', 'products.id')
            ->where('product_stocks.supplier_id', '=', $request->supplier_id)
            ->where('product_stocks.store_id', \Auth::guard('admin')->user()->store_id)
            ->where('product_stocks.quantity', '>', 0)
            ->groupBy('product_stocks.id')
            ->orderBy('products.id', 'desc')->get();

        $htmlc = "<option value=''>Select an Option</option>";
        foreach ($products as $p) {
            $htmlc .= "<option value='$p->id'>$p->product_title ($p->invoice_no) ($p->batch_no)</option>";
        }

        return response()->json(['products' => $htmlc]);
    }

    public function searchProductsDetails(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $data = productStock::where(['store_id' => $store_id, 'id' => $request->product_id])->first();
        $pdt = DB::table('products')->where(['id' => $data->product_id, 'store_id' => $store_id])->first();
        $unit = DB::table('units')->where(['id' => $pdt->unit_id, 'store_id' => $store_id])->first();
        $grandtotal = $data->buy_price_with_tax * $data->quantity;

        $quantity = $data->quantity;
        return response()->json(['data' => $data, 'unit' => $unit->unit_name, 'grandtotal' => $grandtotal]);
    }

    public function purchaseReturnProducts(Request $request)
    {

        $product = new PurchaseReturns();

        $store_id = \Auth::guard('admin')->user()->store_id;
        $productDetails = Product::where(['id' => $request->product_id, 'store_id' => $store_id])->first();
        $messages = [
            'invoice_no.required' => 'Invoice No is Required',
            'supplier_id.required' => 'Supplier Name is Required',
            'product_id.required' => 'Product Name is Required',
            'quantity.required' => 'Product Quantity is Required',
            'quantity.not_in' => 'Product Quantity Can not be zero - "0"',
            'return_date.required' => 'Return Date is Required',
        ];
        $validator = \Validator::make($request->all(), [

            'quantity' => 'required|number|not_in:0',
            'invoice_no' => 'required',
            'supplier_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'return_date' => 'required',

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {


            $cart = session()->get('purchaseReturn', []);
            $cartId = md5(uniqid(rand(), true));



            if (isset($cart[$cartId])) {
                $cart[$cartId]['quantity']++;
            } else {
                $cart[$cartId] = [
                    "pr_hash_id" =>  $cartId,
                    "invoice_no" => $request->invoice_no,
                    "pi_invoice" => $request->pi_invoice,
                    "product_id" => $request->pid,
                    "supplier_id" => $request->supplier_id,
                    "pdtstock_id" => $request->pdtstock_id,
                    "quantity" => $request->quantity,
                    "rate" => $request->buy_price,
                    "amount" => $request->buy_price * $request->quantity,
                    "pur_return_status" => 1,
                    "store_id" => $store_id,
                    "return_date" => Carbon::parse($request->return_date)->toDatetimeString(),
                    'created_at' => Carbon::parse(now())->toDatetimeString(),
                    'updated_at' => Carbon::parse(now())->toDatetimeString(),
                ];
            }
            session()->put('purchaseReturn', $cart);
            $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
            $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
            $i = 1;
            $total = 0;
            $totalprice = 0;
            foreach (session('purchaseReturn') as $p) {
                $total = $total + ($p['rate'] * $p['quantity']);
                $productDetails = DB::table('products')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
                $pdtname = $productDetails->product_title;
                $html .= "<tr>";
                $html .= "<td>" . $i++ . "</td>" . "<td>" . $pdtname . "</td>" . "<td>" . $p['pi_invoice'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['pr_hash_id'] . '" id="deletePdtBtn">X</button></td>';
                $html .= "</tr>";
            }
            $html .= "</table>";

            $html1 = '
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Description</label>
                      <div class="col-sm-9">
                        <input type="text" name="description" class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Total</label>
                      <div class="col-sm-9">
                        <input type="text" name="total" class="form-control form-control-sm" readonly value="' . $total . '" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Discount</label>
                      <div class="col-sm-9">
                        <input type="text" name="discount" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Received</label>
                      <div class="col-sm-9">
                        <input type="text" name="paid" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Received In</label>
                      <div class="col-sm-9">
                        <select class="form-control form-control-sm" name="credit">
                    ';
            foreach ($accounts as $ac) {
                $html1 .= '<option value="' . $ac->id . '">' . $ac->account_name . '</option>';
            }
            $html1 .= '
                        </select>
                      </div>
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                  <div class="form-group">
                    <label for=""></label>
                    <button type="submit" class="btn btn-block btn-info">Save</button>
                  </div>
                
              </div>
            </div>
            ';

            if (!$cart) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Brand has been successfully saved', 'html' => $html, 'html1' => $html1]);
                // return view('dashboard.admin.productManagement.searchResult1')->render();
            }
        }
    }

    public function deletePrCart(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $cartId = $request->cartId;
        if ($cartId) {
            $cart = session()->get('purchaseReturn');
            if (isset($cart[$cartId])) {
                unset($cart[$cartId]);
                session()->put('purchaseReturn', $cart);
            }
            $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
            $i = 1;
            $total = 0;
            $totalprice = 0;
            foreach (session('purchaseReturn') as $p) {
                $total = $total + ($p['rate'] * $p['quantity']);
                $productDetails = DB::table('products')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
                $pdtname = $productDetails->product_title;
                $html .= "<tr>";
                $html .= "<td>" . $i++ . "</td>" . "<td>" . $pdtname . "</td>" . "<td>" . $p['pi_invoice'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['pr_hash_id'] . '" id="deletePdtBtn">X</button></td>';
                $html .= "</tr>";
            }
            $html .= "</table>";

            $html1 = '
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Description</label>
                      <div class="col-sm-9">
                        <input type="text" name="description" class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Total</label>
                      <div class="col-sm-9">
                        <input type="text" name="total" class="form-control form-control-sm" readonly value="' . $total . '" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Discount</label>
                      <div class="col-sm-9">
                        <input type="text" name="discount" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Received</label>
                      <div class="col-sm-9">
                        <input type="text" name="paid" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Received In</label>
                      <div class="col-sm-9">
                        <select class="form-control form-control-sm" name="credit">
                    ';
            foreach ($accounts as $ac) {
                $html1 .= '<option value="' . $ac->id . '">' . $ac->account_name . '</option>';
            }
            $html1 .= '
                        </select>
                      </div>
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                  <div class="form-group">
                    <label for=""></label>
                    <button type="submit" class="btn btn-block btn-info">Save</button>
                  </div>
                
              </div>
            </div>
            ';
        }



        return response()->json(['code' => 1, 'msg' => 'Brand has been successfully saved', 'html' => $html, 'html1' => $html1]);
    }

    public function purchaseReturn1(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $query = PurchaseReturns::insert(session('purchaseReturn'));
        $total = 0;
        $supplier_id = '';
        $invoice_no = '';
        foreach (session('purchaseReturn') as $p) {
            $total = $total + ($p['rate'] * $p['quantity']);
            $supplier_id = $p['supplier_id'];
            $invoice_no = $p['invoice_no'];
            DB::statement("update product_stocks set quantity=quantity-" . $p['quantity'] . " where store_id = '" . $store_id . "' AND id = '" . $p['pdtstock_id'] . "'");
        }
        $spid = DB::table('suppliers')->where(['id' => $supplier_id, 'store_id' => $store_id])->first();
        $due = $total - ($request->discount + $request->paid);
        $discount = $request->discount;
        $paid = $request->paid;
        $acc_code = Account_Types::where(['id' => $request->credit])->first()->code;

        // if ($total == ($request->due + $request->discount + $request->paid)) {


        // }

        $save_data = [];
        if ($total == $paid) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => 'Purchased goods are returned back to supplier',
                'amount' => $paid,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 105,
                'description' => 'Purchased goods are returned back to supplier',
                'amount' => $paid,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);
        }

        if (($discount > 0) and $due == 0 and ($paid + $discount == $total)) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => 'Purchased goods are returned back to supplier in cash with discount',
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 301,
                'description' => 'Purchased goods are returned back to supplier in cash with discount',
                'amount' => $paid,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 105,
                'description' => 'Purchased goods are returned back to supplier in cash with discount',
                'amount' => $total,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);
        }

        if (($due == $total)) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $spid->parent_id, //supplier id
                'description' => 'Purchased goods are returned back to supplier on credit',
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 105,
                'description' => 'Purchased goods are returned back to supplier on credit',
                'amount' => $due,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);
        }

        if ($discount > 0 and $due > 0 and ($due + $discount == $total)) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $spid->parent_id,  //supplier
                'description' => 'Purchased goods are returned back to supplier on credit with discount',
                'amount' => $due,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 306,
                'description' => 'Purchased goods are returned back to supplier on credit with discount',
                'amount' => $discount,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 105,
                'description' => 'Purchased goods are returned back to supplier on credit with discount',
                'amount' => $total,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];

            Transactions::insert($save_data);
        }

        if (($due > 0) and $discount > 0 and $paid > 0 and ($total == ($due + $discount + $paid))) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => 'Purchased goods are returned back to supplier in cash ' . $paid,
                'amount' => $paid,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $spid->parent_id,
                'description' => 'Purchased goods are returned back to supplier on credit with discount',
                'amount' => $due,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 306,
                'description' => 'Purchased goods are returned back to supplier on credit with discount',
                'amount' => $discount,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => 105,
                'description' => 'Purchased goods are returned back to supplier on credit with discount',
                'amount' => $total,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];

            Transactions::insert($save_data);
        }

        session()->forget('purchaseReturn');
        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Purchase Successfully']);
        }
    }
}
