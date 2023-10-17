<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\Customer;
use App\Models\Admin\Product;
use App\Models\Admin\productStock;
use App\Models\Admin\Sale;
use App\Models\Admin\SaleProduct;
use App\Models\Admin\SaleReturns;
use App\Models\Admin\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalesController extends Controller
{
  public function index()
  {

    $test['data'] = ['name' => 'Arif'];
    $store_id = \Auth::guard('admin')->user()->store_id;
    $invtotal = DB::table('sales')->select('id')->where(['store_id' => $store_id])->get();
    session()->forget('pdtsalecart');
    $inv = 'SI-' . date('ymd') . '-' . count($invtotal) + 1;
    $customers = DB::table('customers')->select('parent_id', 'is_walkin', 'customer_name')->where(['customer_status' => 1, 'store_id' => $store_id])->get();
    $data = ['customers' => $customers, 'store_id' => $store_id, 'inv' => $inv];
    return view('dashboard.admin.sales.sales', $data);
    //return view('demo', $data);
  }

  public function printBarcode()
  {
    $test['data'] = ['name' => 'Arif'];
    return view('dashboard.admin.productManagement.printBarcode');
    //return view('demo', $data);
  }

  public function barAction(Request $request)
  {
    $data['quantity'] = $request->quantity;
    $data['type'] = $request->bar_type;
    $data['barcode'] = $request->barcode;
    $html1 = view('dashboard.admin.productManagement.barcode', $data)->render();
    if (!$data) {
      return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    } else {
      return response()->json(['code' => 1, 'msg' => 'Customers List', 'html1' => $html1]);
    }
  }

  public function searchResult(Request $request)
  {
    $product =  DB::select("select t1.id, t1.product_title, t1.product_hash_id, ps.* from products t1 inner join product_stocks ps on t1.id = ps.product_id WHERE t1.product_title LIKE CONCAT('" . $request->search_string . "', '%')");
  }



  public function searchProductsforSale(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    if (empty($request->search_string)) {
      return response()->json(['status' => 'a']);
    }
    $products =  DB::select("select t1.id, t1.product_title, t1.product_hash_id, ps.* from products t1 inner join product_stocks ps on t1.id = ps.product_id WHERE (t1.product_title LIKE CONCAT('%', '" . $request->search_string . "', '%') AND t1.store_id = '" . $store_id . "')");
    $htmlc = "";
    foreach ($products as $p) {
      $htmlc .= "<option  value='" . $p->id . "'>$p->product_title $p->batch_no</option>";
    }
    // $htmlc .= "</ul>";

    return response()->json(['products' => $htmlc]);
  }


  public function searchProductsDetails1(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $pdtid = Product::where(['store_id' => $store_id, 'product_title' => $request->product_id])->first();

    $data = productStock::where(['store_id' => $store_id, 'id' => $pdtid->product_id])->first();
    $pdt = DB::table('products')->where(['id' => $data->product_id, 'store_id' => $store_id])->first();
    $unit = DB::table('units')->where(['id' => $pdt->unit_id, 'store_id' => $store_id])->first();
    $grandtotal = $data->buy_price_with_tax * $data->quantity;
    $quantity = $data->quantity;
    return response()->json(['data' => $data, 'unit' => $unit->unit_name, 'grandtotal' => $grandtotal]);
  }

  public function searchProductsDetails2(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    //$pdtid = Product::where(['store_id' => $store_id, 'product_title' => $request->product_id])->first();

    $data = productStock::where(['store_id' => $store_id, 'id' => $request->product_id])->first();
    $pdt = DB::table('products')->where(['id' => $data->product_id, 'store_id' => $store_id])->first();
    $unit = DB::table('units')->where(['id' => $pdt->unit_id, 'store_id' => $store_id])->first();
    $quantity = $request->quantity;
    $grandtotal = $data->sell_price * $quantity;


    return response()->json(['data' => $data, 'pdtname' => $pdt->product_title, 'unit' => $unit->unit_name, 'grandtotal' => $grandtotal]);
  }

  public function salesAction(Request $request)
  {
    $product = new Sale();
    $store_id = \Auth::guard('admin')->user()->store_id;
    $productDetails = productStock::where(['id' => $request->pdtstock_id, 'store_id' => $store_id])->first();
    $bal = DB::select("select tr.account_head_id, sp.customer_address, sp.customer_phone, ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join customers sp on ac.code = sp.parent_id WHERE tr.account_head_id = $request->customer_id AND ac.acc_type = 'customer' AND tr.store_id = $store_id group by ac.account_name order by tr.account_head_id, ac.account_name");

    $messages = [
      'invoice_no.required' => 'Invoice No is Required',
      'search.required' => 'Product Name is Required',
      'quantity.required' => 'Product Quantity is Required',
      'quantity.not_in' => 'Product Quantity Can not be zero - "0"',
    ];
    $validator = \Validator::make($request->all(), [

      'quantity' => 'required|number|not_in:0',
      'invoice_no' => 'required',
      'search' => 'required',
      'quantity' => 'required',

    ], $messages);

    if (!$validator->passes()) {
      return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
    } else {


      $cart = session()->get('pdtsalecart', []);
      $cartId = md5(uniqid(rand(), true));



      if (isset($cart[$cartId])) {
        $cart[$cartId]['quantity']++;
      } else {
        $cart[$cartId] = [
          "sale_hash_id" =>  $cartId,
          "customer_id" => $request->customer_id,
          "product_id" => $productDetails->product_id,
          "pdtstock_id" => $request->pdtstock_id,
          "invoice_no" => $request->invoice_no,
          "quantity" => $request->quantity,
          "rate" => $request->sell_price,
          "invoice_no" => $request->invoice_no,
          "sale_by" => \Auth::guard('admin')->user()->id,
          "store_id" => $store_id,
          'created_at' => Carbon::parse(now())->toDatetimeString(),
          'updated_at' => Carbon::parse(now())->toDatetimeString(),
        ];
      }
      session()->put('pdtsalecart', $cart);
      $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
      $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
      $i = 1;
      $total = 0;
      $totalprice = 0;
      foreach (session('pdtsalecart') as $p) {
        $total = $total + ($p['rate'] * $p['quantity']);
        $productDetails1 = DB::table('products')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
        // $pdtname = $productDetails->product_title;
        $html .= "<tr>";
        $html .= "<td>" . $i++ . "</td>" . "<td>" . $productDetails1->product_title . "</td>" . "<td>" . $p['invoice_no'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['sale_hash_id'] . '" id="deletePdtBtn">X</button></td>';
        $html .= "</tr>";
      }
      $html .= "</table>";

      $addbal = '';
      $paidbal = 0;
      if ($bal) {
        if ($bal[0]->balance < 0) {
          $ahd =  DB::table('account_types')->where(['code' => $bal[0]->account_head_id, 'store_id' => $store_id])->first();
          $addbal = '<option value="' . $ahd->id . '">Use Balance</option>';
          $paidbal1 = -1 * ($bal[0]->balance);
          if ($paidbal1 > $total) {
            $paidbal = $total;
          } else {
            $paidbal = $paidbal1;
          }
        }
      }

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
                        <input type="text" name="paid" value="' . $paidbal . '"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Received In</label>
                      <div class="col-sm-9">
                        <select class="form-control form-control-sm accdetail" name="credit">
                    ';
      $html1 .= $addbal;
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

  public function deleteSlCart(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
    $bal = DB::select("select tr.account_head_id, sp.customer_address, sp.customer_phone, ac.account_name, sum(tr.amount * tr.direction * ac.normal) as balance from transactions tr left join account_types ac on tr.account_head_id = ac.code left join customers sp on ac.code = sp.parent_id WHERE tr.account_head_id = $request->customer_id AND ac.acc_type = 'customer' AND tr.store_id = $store_id group by ac.account_name order by tr.account_head_id, ac.account_name");
    $cartId = $request->cartId;
    if ($cartId) {
      $cart = session()->get('pdtsalecart');
      if (isset($cart[$cartId])) {
        unset($cart[$cartId]);
        session()->put('pdtsalecart', $cart);
      }
      $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
      $i = 1;
      $total = 0;
      $totalprice = 0;
      foreach (session('pdtsalecart') as $p) {
        $total = $total + ($p['rate'] * $p['quantity']);
        $productDetails = DB::table('products')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
        $pdtname = $productDetails->product_title;
        $html .= "<tr>";
        $html .= "<td>" . $i++ . "</td>" . "<td>" . $pdtname . "</td>" . "<td>" . $p['invoice_no'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['sale_hash_id'] . '" id="deletePdtBtn">X</button></td>';
        $html .= "</tr>";
      }
      $html .= "</table>";

      $addbal = '';
      $paidbal = 0;
      if ($bal) {
        if ($bal[0]->balance < 0) {
          $ahd =  DB::table('account_types')->where(['code' => $bal[0]->account_head_id, 'store_id' => $store_id])->first();
          $addbal = '<option value="' . $ahd->id . '">Use Balance</option>';
          $paidbal1 = -1 * ($bal[0]->balance);
          if ($paidbal1 > $total) {
            $paidbal = $total;
          }
        }
      }

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
                        <input type="text" name="paid" value="' . $paidbal . '"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Received In</label>
                      <div class="col-sm-9">
                        <select class="form-control form-control-sm accdetail" name="credit">
                    ';
      $html1 .= $addbal;
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


  public function addCustomerinSales(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $messages = [
      'customer_name.required' => 'Customer Name is Required',
      'customer_phone.required' => 'Customer Phone is Required',
      'customer_address.required' => 'Customer Address is Required',
      'customer_phone.min' => 'Enter minimum 11 numbers',

    ];
    $validator = \Validator::make($request->all(), [
      'customer_name' => [
        'required',
        Rule::unique('customers')->where(function ($query) use ($store_id) {
          return $query->where(['store_id' => $store_id]);
        }),

      ],
      'customer_phone' => 'required|min:11|max:15',
      'customer_address' => 'required',
    ], $messages);

    if (!$validator->passes()) {
      return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
    } else {

      $accounttype = new Account_Types();
      $accounttype->account_type_hash_id =  md5(uniqid(rand(), true));
      $accounttype->account_head_id = 1;
      $accounttype->account_name = $request->customer_name;
      $accounttype->store_id = \Auth::guard('admin')->user()->store_id;
      $accounttype->is_money = 0;
      $accounttype->code = Account_Types::where(['account_head_id' => 1])->max('code') + 1;
      $accounttype->normal = 1;
      $accounttype->acc_type = 'customer';
      $accounttype->acctype_status = 1;
      $query = $accounttype->save();

      $customer = new Customer();
      $customer->customer_hash_id =  md5(uniqid(rand(), true));
      $customer->customer_name = $request->customer_name;
      $customer->customer_address = $request->customer_address;
      $customer->customer_phone = $request->customer_phone;
      $customer->customer_email = $request->customer_email;
      $customer->store_id = \Auth::guard('admin')->user()->store_id;
      $customer->customer_status = 1;
      $customer->parent_id = $accounttype->code;
      $query = $customer->save();

      $unitlist = Customer::where(['customer_status' => 1, 'store_id' => $store_id])->get();
      $htmld = "";
      foreach ($unitlist as $c) {
        if ($unitlist->last() == $c) {
          $a = "selected";
        } else {
          $a = "";
        }
        $htmld .= "<option value='$c->parent_id' $a>$c->customer_name</option>";
      }

      if ($query) {
        return response()->json(['code' => 1, 'msg' => 'Customer Added Successfully', 'unititem' => $htmld]);
      } else {
        return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
      }
    }
  }

  public function finalSale(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $allaccount = DB::table('account_types')->select('id', 'account_head_id', 'account_name', 'is_money', 'code')->where(['store_id' => $store_id, 'acctype_status' => 1])->get();
    $sales_revenue = $allaccount->where('account_name', 'Sales Revenue')->pluck('code')->first();
    $discount_allowed = $allaccount->where('account_name', 'Discount Allowed')->pluck('code')->first();
    SaleProduct::insert(session('pdtsalecart'));
    $total = 0;
    $customer_id = '';
    $invoice_no = '';
    foreach (session('pdtsalecart') as $p) {
      $total = $total + ($p['rate'] * $p['quantity']);
      $customer_id = $p['customer_id'];
      $invoice_no = $p['invoice_no'];
      DB::statement("update product_stocks set quantity=quantity-" . $p['quantity'] . " where store_id = '" . $store_id . "' AND id = '" . $p['pdtstock_id'] . "'");
    }

    $due = $total - ($request->discount + $request->paid);
    $discount = $request->discount;
    $paid = $request->paid;
    $acc_code1 = Account_Types::where(['id' => $request->credit])->first();

    $acc_code = $acc_code1->code;


    $check_pending = 0;
    if ($acc_code1->account_name == "Bank Cheque") {
      $acc_code = $customer_id;
      $paid = 0;
      $due = $total - $discount;
      $check_pending = 1;
    }



    $sales = new Sale();

    $sales->customer_id = $customer_id;
    $sales->invoice_no = $invoice_no;
    $sales->trns_type = $acc_code1->account_name;
    $sales->description = $request->description;
    $sales->total = $total;
    $sales->due = $due;
    $sales->discount = $discount;
    $sales->paid = $paid;
    $sales->sale_status = 1; // due, etc
    $sales->check_pending = $check_pending;
    $sales->store_id = $store_id;
    $query = $sales->save();
    // }

    $save_data = [];
    if ($total == $paid) {
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $acc_code,  //401 sales revenue  
        'description' => 'Goods sold in cash ' . $paid,
        'amount' => $paid,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue, //sales revenue
        'description' => 'Goods sold in cash' . $paid,
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
        'description' => 'Goods sold in cash with discount ' . $discount,
        'amount' => $paid,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $discount_allowed,  //expenses discount allowed
        'description' => 'Goods sold in cash with discount ' . $discount,
        'amount' => $discount,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,   //sales revenue
        'description' => 'Goods sold in cash with discount ' . $discount,
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
        'account_head_id' => $customer_id,
        'description' => 'Goods sold on credit',
        'amount' => $total,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,
        'description' => 'Goods sold on credit',
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
        'account_head_id' => $customer_id,
        'description' => 'Goods Sold on credit with discount',
        'amount' => $due,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $discount_allowed,  //expenses discount allowed
        'description' => 'Goods Sold on credit with discount',
        'amount' => $discount,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,
        'description' => 'Goods sold on credit with discount',
        'amount' => $total,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      Transactions::insert($save_data);
    }

    if (($due > 0) and ($discount > 0) and ($paid > 0) and ($total == ($due + $discount + $paid))) {
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $acc_code,
        'description' => '--Goods Sold in cash ' . $paid,     //paid + discount 
        'amount' => $paid,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $customer_id,
        'description' => '--Goods Sold on credit with discount',
        'amount' => $due,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $discount_allowed,  //expenses discount allowed
        'description' => '--Goods Sold on credit with discount',
        'amount' => $discount,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,
        'description' => '--Goods Sold on credit with discount',
        'amount' => $total,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      Transactions::insert($save_data);
    }

    if (($due > 0) and ($paid > 0) and ($discount == 0) and ($total == ($due + $paid))) {
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $acc_code,
        'description' => '--Goods Sold in cash ' . $paid,     //paid + discount 
        'amount' => $paid,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $customer_id,
        'description' => '--Goods Sold on credit with discount',
        'amount' => $due,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];

      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,
        'description' => '--Goods Sold on credit with discount',
        'amount' => $total,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      Transactions::insert($save_data);
    }


    $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
    $datas['sales'] = DB::table('sales')->where(['invoice_no' => $invoice_no, 'store_id' => $store_id])->first();

    $datas['salepdts'] = DB::table('sale_products')
      ->join('products', 'products.id', '=', 'sale_products.product_id')
      ->select('products.product_title', 'sale_products.*')
      ->where(['sale_products.invoice_no' => $invoice_no, 'sale_products.store_id' => $store_id])
      ->get();
    $datas['customer'] = DB::table('customers')->where(['parent_id' => $customer_id, 'store_id' => $store_id])->first();


    $html1 = view('dashboard.admin.reports.accounts.sample_report.invoice', $datas)->render();
    session()->forget('pdtsalecart');
    $invtotal = DB::table('sales')->where(['store_id' => $store_id])->get();
    $inv = 'SI-' . date('ymd') . '-' . count($invtotal) + 1;

    if (!$query) {
      return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    } else {
      return response()->json(['code' => 1, 'msg' => 'Product Sold Successfully', 'html1' => $html1, 'inv' => $inv]);
    }
  }

  public function salesReturn()
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $invtotal = DB::table('sale_returns')->select('id')->where(['store_id' => $store_id])->get();
    session()->forget('pdtsalereturncart');
    $inv = 'SR-' . date('ymd') . '-' . count($invtotal) + 1;
    $customers = DB::table('customers')->select('parent_id', 'customer_name', 'customer_phone')->where(['customer_status' => 1, 'store_id' => $store_id])->get();
    $data = ['customers' => $customers, 'store_id' => $store_id, 'inv' => $inv];
    return view('dashboard.admin.sales.sales_return', $data);
  }

  public function searchSalesReturn1(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $products = SaleProduct::select('products.product_title', 'sale_products.*')
      ->leftJoin('products', 'sale_products.product_id', '=', 'products.id')
      ->where('sale_products.invoice_no', '=', $request->invoice)
      ->where('sale_products.store_id', \Auth::guard('admin')->user()->store_id)
      ->groupBy('sale_products.id')
      ->orderBy('products.id', 'desc')->get();
    $htmlc = "<option value=''>--Select a Product--</option>";
    foreach ($products as $p) {
      $htmlc .= "<option value='$p->id'>" . $p->product_title . "</option>";
    }

    return response()->json(['pdts' => $htmlc]);
  }

  public function searchSalesReturn2(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $products = SaleProduct::select('products.product_title', 'sale_products.*')
      ->leftJoin('products', 'sale_products.product_id', '=', 'products.id')
      ->where('sale_products.customer_id', '=', $request->customerid)
      ->where('sale_products.store_id', \Auth::guard('admin')->user()->store_id)
      ->groupBy('sale_products.id')
      ->orderBy('products.id', 'desc')->get();
    $htmlc = "<option value=''>--Select a Product--</option>";
    foreach ($products as $p) {
      $htmlc .= "<option value='$p->id'>" . $p->product_title . " (" .  $p->invoice_no . ")</option>";
    }

    return response()->json(['pdts' => $htmlc]);
  }

  public function salesReturnAction(Request $request)
  {

    $product = new Sale();
    $store_id = \Auth::guard('admin')->user()->store_id;
    $inv = $request->inv;
    $salesid = SaleProduct::where(['id' => $request->pdtstockid, 'store_id' => $store_id])->first();
    $productDetails = productStock::where(['id' => $salesid->pdtstock_id, 'store_id' => $store_id])->first();
    $messages = [
      'pdtstockid.required' => 'Invoice No is Required',
      'quantity.required' => 'Product Name is Required',
    ];
    $validator = \Validator::make($request->all(), [

      'quantity' => 'required|number|not_in:0',
      'quantity' => 'required',
      'pdtstockid' => 'required',

    ], $messages);

    if (!$validator->passes()) {
      return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
    } else {


      $cart = session()->get('pdtsalereturncart', []);
      $cartId = md5(uniqid(rand(), true));



      if (isset($cart[$cartId])) {
        $cart[$cartId]['quantity']++;
      } else {
        $cart[$cartId] = [
          "sale_return_hash_id" =>  $cartId,
          "invoice_no" => $inv,
          "customer_id" => $request->customer_id,
          "product_id" => $productDetails->product_id,
          "pdtstock_id" => $salesid->pdtstock_id,
          "sale_invoice" => $salesid->invoice_no,
          "quantity" => $request->quantity,
          "rate" => $request->sell_price,
          "return_by" => \Auth::guard('admin')->user()->id,
          "store_id" => $store_id,
          'created_at' => Carbon::parse(now())->toDatetimeString(),
          'updated_at' => Carbon::parse(now())->toDatetimeString(),
        ];
      }
      session()->put('pdtsalereturncart', $cart);

      $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
      $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
      $i = 1;
      $total = 0;
      $totalprice = 0;
      foreach (session('pdtsalereturncart') as $p) {
        $total = $total + ($p['rate'] * $p['quantity']);
        $productDetails1 = DB::table('products')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
        // $pdtname = $productDetails->product_title;
        $html .= "<tr>";
        $html .= "<td>" . $i++ . "</td>" . "<td>" . $productDetails1->product_title . "</td>" . "<td>" . $p['sale_invoice'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['sale_return_hash_id'] . '" id="deletePdtBtn">X</button></td>';
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
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Return Amount</label>
                      <div class="col-sm-9">
                        <input type="text" name="paid" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Return From</label>
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

  public function deleteSlRetCart(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
    $cartId = $request->cartId;
    if ($cartId) {
      $cart = session()->get('pdtsalereturncart');
      if (isset($cart[$cartId])) {
        unset($cart[$cartId]);
        session()->put('pdtsalereturncart', $cart);
      }
      $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
      $i = 1;
      $total = 0;
      $totalprice = 0;
      foreach (session('pdtsalereturncart') as $p) {
        $total = $total + ($p['rate'] * $p['quantity']);
        $productDetails = DB::table('products')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
        $pdtname = $productDetails->product_title;
        $html .= "<tr>";
        $html .= "<td>" . $i++ . "</td>" . "<td>" . $pdtname . "</td>" . "<td>" . $p['sale_invoice'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['sale_return_hash_id'] . '" id="deletePdtBtn">X</button></td>';
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
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Return Amount</label>
                      <div class="col-sm-9">
                        <input type="text" name="paid" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Return From</label>
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

  public function searchProductsDetails3(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $solditems = SaleProduct::where(['store_id' => $store_id, 'id' => $request->product_id])->first();
    $data = productStock::where(['store_id' => $store_id, 'id' => $solditems->pdtstock_id])->first();
    $pdt = DB::table('products')->where(['id' => $data->product_id, 'store_id' => $store_id])->first();
    $unit = DB::table('units')->where(['id' => $pdt->unit_id, 'store_id' => $store_id])->first();
    $quantity = $request->quantity;
    $grandtotal = $solditems->rate * $quantity;


    return response()->json(['data' => $data, 'solditems' => $solditems, 'pdtname' => $pdt->product_title, 'unit' => $unit->unit_name, 'grandtotal' => $grandtotal]);
  }

  public function finalSaleReturn(Request $request)
  {
    $store_id = \Auth::guard('admin')->user()->store_id;
    $allaccount = DB::table('account_types')->select('id', 'account_head_id', 'account_name', 'is_money', 'code')->where(['store_id' => $store_id, 'acctype_status' => 1])->get();
    $sales_revenue = $allaccount->where('account_name', 'Sales Revenue')->pluck('code')->first();
    $query = SaleReturns::insert(session('pdtsalereturncart'));
    $total = 0;
    $customer_id = '';
    $invoice_no = '';
    foreach (session('pdtsalereturncart') as $p) {
      $total = $total + ($p['rate'] * $p['quantity']);
      $customer_id = $p['customer_id'];
      $invoice_no = $p['invoice_no'];
      DB::statement("update product_stocks set quantity=quantity+" . $p['quantity'] . " where store_id = '" . $store_id . "' AND id = '" . $p['pdtstock_id'] . "'");
    }

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
        'account_head_id' => $sales_revenue,  //401 sales revenue  
        'description' => 'Goods sales return',
        'amount' => $total,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $acc_code, //acc_code = cash or bank
        'description' => 'Goods sales return',
        'amount' => $paid,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      Transactions::insert($save_data);
    }



    if (($due == $total)) {
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,
        'description' => 'Goods sales return',
        'amount' => $total,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $customer_id,
        'description' => 'Goods sales return',
        'amount' => $due,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      Transactions::insert($save_data);
    }



    if (($due > 0) and  $paid > 0 and ($total == ($due +  $paid))) {
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $sales_revenue,  //401 sales revenue  
        'description' => 'Goods sales return',
        'amount' => $total,
        'direction' => 1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $acc_code, //acc_code = cash or bank
        'description' => 'Goods sales return',
        'amount' => $paid,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      $save_data[] = [
        'trns_id' => $invoice_no,
        'account_head_id' => $customer_id, //acc_code = cash or bank
        'description' => 'Goods sales return',
        'amount' => $due,
        'direction' => -1,
        'trns_date' => date('Y-m-d'),
        'store_id' => $store_id
      ];
      Transactions::insert($save_data);
    }

    session()->forget('pdtsalecart');


    if (!$query) {
      return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    } else {
      return response()->json(['code' => 1, 'msg' => 'Sales Return Successfully Completed']);
    }
  }
}
