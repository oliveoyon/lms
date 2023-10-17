<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\DamageProducts;
use App\Models\Admin\Location;
use App\Models\Admin\Product;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\productStock;
use App\Models\Admin\Purchase;
use App\Models\Admin\Sale;
use App\Models\Admin\Transactions;
use App\Models\Admin\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function addProduct()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $manufacturers = DB::table('manufacturers')->select('id', 'manufacturer_name')->where(['manufacturer_status' => 1, 'store_id' => $store_id])->get();
        $units = DB::table('units')->select('id', 'unit_name')->where(['unit_status' => 1, 'store_id' => $store_id])->get();
        $categories = DB::table('categories')->select('id', 'category_name')->where(['category_status' => 1, 'store_id' => $store_id])->get();
        $brands = DB::table('brands')->select('id', 'brand_name')->where(['brand_status' => 1, 'store_id' => $store_id])->get();
        $shelfs = DB::table('locations')->select('id', 'shelf_name')->where(['shelf_status' => 1, 'store_id' => $store_id])->get();
        $data = ['manufacturers' => $manufacturers, 'categories' => $categories, 'brands' => $brands, 'shelfs' => $shelfs, 'units' => $units];
        return view('dashboard.admin.productManagement.addProduct', $data);
    }

    public function addmanufacturerinpdt(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'manufacturer_name.required' => 'Manufacturer Name is Required',
            'manufacturer_name.unique' => 'Manufacturer Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'manufacturer_name' => [
                'required',
                Rule::unique('manufacturers')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $manufacturer = new Manufacturer();
            $manufacturer->manufacturer_hash_id =  md5(uniqid(rand(), true));
            $manufacturer->manufacturer_name = $request->manufacturer_name;
            $manufacturer->store_id = \Auth::guard('admin')->user()->store_id;
            $manufacturer->manufacturer_status = 1;
            $query = $manufacturer->save();

            $manufacturerlist = Manufacturer::where(['manufacturer_status' => 1, 'store_id' => $store_id])->get();
            $htmlm = "";
            foreach ($manufacturerlist as $m) {
                if ($manufacturerlist->last() == $m) {
                    $a = "selected";
                } else {
                    $a = "";
                }
                $htmlm .= "<option value='$m->id' $a>$m->manufacturer_name</option>";
            }


            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Manufacturer Details Added Successfully', 'manufactureritem' => $htmlm]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }


    public function addcategoryinpdt(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'category_name.required' => 'Category Name is Required',
            'category_name.unique' => 'Category Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'category_name' => [
                'required',
                Rule::unique('categories')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $category = new Category();
            $category->category_hash_id =  md5(uniqid(rand(), true));
            $category->category_name = $request->category_name;
            $category->store_id = \Auth::guard('admin')->user()->store_id;
            $category->category_status = 1;
            $category->category_img = 'dfd';
            $query = $category->save();

            $categorylist = Category::where(['category_status' => 1, 'store_id' => $store_id])->get();
            $htmlc = "";
            foreach ($categorylist as $c) {
                if ($categorylist->last() == $c) {
                    $a = "selected";
                } else {
                    $a = "";
                }
                $htmlc .= "<option value='$c->id' $a>$c->category_name</option>";
            }


            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Category Details Added Successfully', 'categoryitem' => $htmlc]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function addbrandinpdt(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'brand_name.required' => 'Brand Name is Required',
            'brand_name.unique' => 'Brand Name Already Exists',
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
            $brand->store_id = \Auth::guard('admin')->user()->store_id;
            $brand->brand_status = 1;
            $query = $brand->save();

            $brandlist = Brand::where(['brand_status' => 1, 'store_id' => $store_id])->get();
            $htmlc = "";
            foreach ($brandlist as $c) {
                if ($brandlist->last() == $c) {
                    $a = "selected";
                } else {
                    $a = "";
                }
                $htmlc .= "<option value='$c->id' $a>$c->brand_name</option>";
            }


            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Brand Details Added Successfully', 'branditem' => $htmlc]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function addunitinpdt(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'unit_name.required' => 'Unit Name is Required',
            'unit_name.unique' => 'Unit Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'unit_name' => [
                'required',
                Rule::unique('units')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $unit = new Unit();
            $unit->unit_hash_id =  md5(uniqid(rand(), true));
            $unit->unit_name = $request->unit_name;
            $unit->store_id = \Auth::guard('admin')->user()->store_id;
            $unit->unit_status = 1;
            $query = $unit->save();

            $unitlist = Unit::where(['unit_status' => 1, 'store_id' => $store_id])->get();
            $htmlc = "";
            foreach ($unitlist as $c) {
                if ($unitlist->last() == $c) {
                    $a = "selected";
                } else {
                    $a = "";
                }
                $htmlc .= "<option value='$c->id' $a>$c->unit_name</option>";
            }


            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Unit Details Added Successfully', 'unititem' => $htmlc]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function addshelfinpdt(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'shelf_name.required' => 'Unit Name is Required',
            'shelf_name.unique' => 'Unit Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'shelf_name' => [
                'required',
                Rule::unique('locations')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $shelf = new Location();
            $shelf->shelf_hash_id =  md5(uniqid(rand(), true));
            $shelf->shelf_name = $request->shelf_name;
            $shelf->store_id = \Auth::guard('admin')->user()->store_id;
            $shelf->shelf_status = 1;
            $query = $shelf->save();

            $shelflist = Location::where(['shelf_status' => 1, 'store_id' => $store_id])->get();
            $htmlc = "";
            foreach ($shelflist as $c) {
                if ($shelflist->last() == $c) {
                    $a = "selected";
                } else {
                    $a = "";
                }
                $htmlc .= "<option value='$c->id' $a>$c->shelf_name</option>";
            }


            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Shelf Details Added Successfully', 'shelfitem' => $htmlc]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function addProducts(Request $request)
    {

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'manufacturer_id.required' => 'Manufacturer Name is Required',
            'category_id.required' => 'Category Name is Required',
            'brand_id.required' => 'Brand Name is Required',
            'unit_id.required' => 'Unit Name is Required',
            'product_title.required' => 'Product Name is Required',
            'product_title.unique' => 'Product Name Already Exists',
            'product_image.image' => 'Category Image muste be an Image',
            'product_image.max' => 'File Size Should be Less Than 1MB',

        ];
        $validator = \Validator::make($request->all(), [
            'product_title' => [
                'required',
                Rule::unique('products')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),

            ],
            'manufacturer_id' => 'required|integer',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'product_image' => 'image',
            'product_image' => 'max:100',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $file_name = '';
            if ($request->file('product_image')) {
                $path = 'images/products/';
                $file = $request->file('product_image');
                $file_name = time() . '_' . $file->getClientOriginalName();
                $upload = $file->storeAs($path, $file_name, 'public');
            }

            if (!$request->barcode) {
                $barcode = $this->generateUniqueCode();
            } else {
                $barcode = $request->barcode;
            }

            $product = new Product();
            $product->product_hash_id =  md5(uniqid(rand(), true));
            $product->barcode = $barcode;
            $product->manufacturer_id = $request->manufacturer_id;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->unit_id = $request->unit_id;
            $product->location_id = $request->location_id;
            $product->product_title = $request->product_title;
            $product->title_slug = \Str::slug($request->product_title);
            $product->pdt_description = $request->pdt_description;
            $product->product_image = $file_name;
            $product->store_id = \Auth::guard('admin')->user()->store_id;
            $query = $product->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Brand has been successfully saved', 'prids' => $product->product_hash_id]);
            }
        }
    }

    public function generateUniqueCode()
    {
        do {
            $code = random_int(10000000, 99999999);
        } while (Product::where("barcode", "=", $code)->first());

        return $code;
    }
    public function generateBatch($id)
    {
        do {
            $code = random_int(10000000, 99999999);
        } while (Product::where("barcode", "=", $code)->first());
        return 'B-' . $code;
    }


    public function addtostock($id = null)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $shelfs = DB::table('locations')->where(['shelf_status' => 1, 'store_id' => $store_id])->get();
        $suppliers = DB::table('suppliers')->where(['supplier_status' => 1, 'store_id' => $store_id])->get();
        $hashId = $id;
        $data = ['shelfs' => $shelfs, 'suppliers' => $suppliers, 'hashId' => $id];
        return view('dashboard.admin.productManagement.addProductStk', $data);
    }


    public function addProductToStock(Request $request)
    {
        $product = new productStock();

        $store_id = \Auth::guard('admin')->user()->store_id;
        $productDetails = Product::where(['product_hash_id' => $request->hashId, 'store_id' => $store_id])->first();
        $messages = [
            'quantity.required' => 'Product Quantity is Required',
            'quantity.not_in' => 'Product Quantity Can not be zero - "0"',
        ];
        $validator = \Validator::make($request->all(), [

            'quantity' => 'required|array|not_in:0',

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $file_name = '';
            if ($request->file('stckpdt_image')) {
                $path = 'images/products/';
                $file = $request->file('stckpdt_image');
                $file_name = time() . '_' . $file->getClientOriginalName();
                $upload = $file->storeAs($path, $file_name, 'public');
            }



            if (!$request->batch_no) {
                $batch_no = $this->generateBatch($productDetails->id);
            } else {
                $batch_no = $request->batch_no;
            }


            $product = new productStock();


            $save_data = [];
            for ($i = 0; $i < count($request->quantity); $i++) {

                if (!$request->barcode[$i]) {
                    $barcode = $this->generateUniqueCode();
                } else {
                    $barcode = $productDetails->id . '-' . $request->barcode[$i];
                }
                $save_data[] = [
                    "pdt_stock_hash_id" =>  md5(uniqid(rand(), true)),
                    "product_id" => $productDetails->id,
                    "invoice_no" => 'Initial Stock',
                    "batch_no" => $batch_no,
                    "supplier_id" => $request->supplier_id,
                    "shelf_id" => $request->shelf_id,
                    "stckpdt_image" => $file_name,
                    "post_by" => \Auth::guard('admin')->user()->id,
                    "store_id" => \Auth::guard('admin')->user()->store_id,
                    "pdtstk_status" => 1,
                    "serial_no" => $request->serial_no[$i],
                    "barcode" => $barcode,
                    "size" => $request->size[$i],
                    "color" => $request->color[$i],
                    "buy_price" => $request->buy_price[$i],
                    "buy_price_with_tax" => $request->buy_price[$i],
                    "sell_price" => $request->sell_price[$i],
                    "quantity" => $request->quantity[$i],
                    "purchase_date" => Carbon::parse($request->purchase_date[$i])->toDatetimeString(),
                    "expired_date" => Carbon::parse($request->expired_date[$i])->toDatetimeString(),
                    'created_at' => Carbon::parse(now())->toDatetimeString(),
                    'updated_at' => Carbon::parse(now())->toDatetimeString(),
                ];
            }

            $query = productStock::insert($save_data);
            // $query = $product->save($save_data);
            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Brand has been successfully saved']);
            }
        }
    }

    public function addProductToStocks()
    {
        return view('dashboard.admin.productManagement.products');
    }

    public function searchResult(Request $request)
    {

        $product = Product::select('products.product_title', 'products.product_hash_id', 'manufacturers.manufacturer_name', 'categories.category_name', 'brands.brand_name', DB::raw('SUM(product_stocks.quantity) As quantity'))
            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where('product_title', 'like', '%' . $request->search_string . '%')
            ->where('products.store_id', \Auth::guard('admin')->user()->store_id)
            ->groupBy('products.id')
            ->orderBy('products.id', 'desc')->get();

        if (empty($request->search_string)) {
            return response()->json(['status' => 'Nothing Found']);
        }
        if (!empty($product)) {
            return view('dashboard.admin.productManagement.searchResult', compact('product'))->render();
        }
    }

    public function searchResultForPurchase(Request $request)
    {

        $store_id = \Auth::guard('admin')->user()->store_id;
        $stock = productStock::where(['store_id' => $store_id, 'product_id' => $request->search_string])->sum('quantity');
        $stocks = Product::select('unit_id')->where(['store_id' => $store_id, 'id' => $request->search_string])->first();
        $unit = Unit::select('id', 'unit_name')->where(['store_id' => $store_id, 'id' => $stocks->unit_id])->first();
        $unit = $unit->unit_name;
        return response()->json(['stock' => $stock, 'unit' => $unit]);
    }

    public function purchaseProduct()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('purchases')->select('id')->where(['store_id' => $store_id])->get();
        session()->forget('cart');
        $inv = 'PI-' . date('ymd') . '-' . count($invtotal) + 1;
        $shelfs = DB::table('locations')->select('id', 'shelf_name')->where(['shelf_status' => 1, 'store_id' => $store_id])->get();
        $products = DB::table('products')->select('id', 'product_title')->where(['product_status' => 1, 'store_id' => $store_id])->get();
        $suppliers = DB::table('suppliers')->select('id', 'supplier_name')->where(['supplier_status' => 1, 'store_id' => $store_id])->get();
        $taxes = DB::table('taxes')->select('id', 'tax_short_name', 'tax_value_percent')->where(['tax_status' => 1, 'store_id' => $store_id])->get();
        $data = ['shelfs' => $shelfs, 'suppliers' => $suppliers, 'store_id' => $store_id, 'products' => $products, 'inv' => $inv, 'taxes' => $taxes];
        return view('dashboard.admin.productManagement.purchaseProduct', $data);
    }

    public function purchaseProducts(Request $request)
    {

        $product = new productStock();

        $store_id = \Auth::guard('admin')->user()->store_id;
        $productDetails = Product::where(['id' => $request->product_id, 'store_id' => $store_id])->first();
        $messages = [
            'invoice_no.required' => 'Invoice No is Required',
            'supplier_id.required' => 'Supplier Name is Required',
            'product_id.required' => 'Product Name is Required',
            'quantity.required' => 'Product Quantity is Required',
            'buy_price.required' => 'Product Price is Required',
            'quantity.not_in' => 'Product Quantity Can not be zero - "0"',
        ];
        $validator = \Validator::make($request->all(), [

            'quantity' => 'required|number|not_in:0',
            'invoice_no' => 'required',
            'supplier_id' => 'required',
            'product_id' => 'required',
            'buy_price' => 'required',
            'quantity' => 'required',

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $file_name = '';

            if (!$request->batch_no) {
                $batch_no = $this->generateBatch($productDetails->id);
            } else {
                $batch_no = $request->batch_no;
            }

            if (!$request->barcode) {
                $barcode = $this->generateUniqueCode();
            } else {
                $barcode = $productDetails->id . '-' . $request->barcode;
            }

            $cart = session()->get('cart', []);
            $cartId = md5(uniqid(rand(), true));

            $singletax = ($request->tax_value_percent / 100) * $request->buy_price;
            $singletotwithtax = $singletax + $request->buy_price;
            // $totaltax = $singletax * $request->quantity;
            // $totalamount = $request->buy_price * $request->quantity;
            // $actualamount = $totaltax + $totalamount;

            // (($request->tax_value_percent / 100) * $request->buy_price) + $request->buy_price

            if (isset($cart[$cartId])) {
                $cart[$cartId]['quantity']++;
            } else {
                $cart[$cartId] = [
                    "pdt_stock_hash_id" =>  $cartId,
                    "invoice_no" => $request->invoice_no,
                    "product_type" => $request->product_type,
                    "product_id" => $productDetails->id,
                    "batch_no" => $batch_no,
                    "supplier_id" => $request->supplier_id,
                    "shelf_id" => $request->shelf_id,
                    "tax_id" => $request->taxes,
                    "tax_value_percent" => $request->tax_value_percent,
                    "stckpdt_image" => $file_name,
                    "post_by" => \Auth::guard('admin')->user()->id,
                    "store_id" => \Auth::guard('admin')->user()->store_id,
                    "pdtstk_status" => 1,
                    "serial_no" => $request->serial_no,
                    "barcode" => $barcode,
                    "size" => $request->size,
                    "color" => $request->color,
                    "buy_price" => $request->buy_price,
                    "buy_price_with_tax" => $singletotwithtax,
                    "sell_price" => $request->sell_price,
                    "quantity" => $request->quantity,
                    "purchase_date" => Carbon::parse($request->purchase_date)->toDatetimeString(),
                    "expired_date" => Carbon::parse($request->expired_date)->toDatetimeString(),
                    'created_at' => Carbon::parse(now())->toDatetimeString(),
                    'updated_at' => Carbon::parse(now())->toDatetimeString(),
                ];
            }
            session()->put('cart', $cart);
            $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
            $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Tax/Unit</th><th>Total Tax</th><th>Actual Amount</th><th>Action</th></tr>";
            $i = 1;
            $total = 0;
            $totalprice = 0;
            foreach (session('cart') as $p) {
                $totalprice = $totalprice + (($p['buy_price_with_tax'] - $p['buy_price']) * $p['quantity']) + ($p['buy_price'] * $p['quantity']);
                $total = $total + ($p['buy_price'] * $p['quantity']);
                $pdtname = $this->getPdtName($p['product_id']);
                $html .= "<tr>";
                $html .= "<td>" . $i++ . "</td>" . "<td>" . $pdtname . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['buy_price'] . "</td>" . "<td>" . $p['buy_price'] * $p['quantity'] . "</td>" . "<td>" . $p['buy_price_with_tax'] - $p['buy_price'] . "</td>" . "<td>" . ($p['buy_price_with_tax'] - $p['buy_price']) * $p['quantity'] . "</td>" . "<td>" . (($p['buy_price_with_tax'] - $p['buy_price']) * $p['quantity']) + ($p['buy_price'] * $p['quantity']) . "</td>" . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['pdt_stock_hash_id'] . '" id="deletePdtBtn">X</button></td>';
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
                        <input type="text" name="total" class="form-control form-control-sm" readonly value="' . $totalprice . '" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Discount</label>
                      <div class="col-sm-9">
                        <input type="text" name="discount" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Paid</label>
                      <div class="col-sm-9">
                        <input type="text" name="paid" value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Paid From</label>
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

    public function getPdtName($id)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $productDetails = Product::where(['id' => $id, 'store_id' => $store_id])->first();
        return $productDetails->product_title;
    }

    public function deletePdtCart(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $cartId = $request->cartId;
        if ($cartId) {
            $cart = session()->get('cart');
            if (isset($cart[$cartId])) {
                unset($cart[$cartId]);
                session()->put('cart', $cart);
            }
            $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Tax/Unit</th><th>Total Tax</th><th>Actual Amount</th><th>Action</th></tr>";
            $i = 1;
            $total = 0;
            $totalprice = 0;
            foreach (session('cart') as $p) {
                $totalprice = $totalprice + (($p['buy_price_with_tax'] - $p['buy_price']) * $p['quantity']) + ($p['buy_price'] * $p['quantity']);
                $total = $total + ($p['buy_price'] * $p['quantity']);
                $pdtname = $this->getPdtName($p['product_id']);
                $html .= "<tr>";
                $html .= "<td>" . $i++ . "</td>" . "<td>" . $pdtname . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['buy_price'] . "</td>" . "<td>" . $p['buy_price'] * $p['quantity'] . "</td>" . "<td>" . $p['buy_price_with_tax'] - $p['buy_price'] . "</td>" . "<td>" . ($p['buy_price_with_tax'] - $p['buy_price']) * $p['quantity'] . "</td>" . "<td>" . (($p['buy_price_with_tax'] - $p['buy_price']) * $p['quantity']) + ($p['buy_price'] * $p['quantity']) . "</td>" . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['pdt_stock_hash_id'] . '" id="deletePdtBtn">X</button></td>';
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
                        <input type="text" name="total" class="form-control form-control-sm" readonly value="' . $totalprice . '" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Discount</label>
                      <div class="col-sm-9">
                        <input type="text" name="discount" value="0" class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Paid</label>
                      <div class="col-sm-9">
                        <input type="text" name="paid"  value="0"  class="form-control form-control-sm" id="colFormLabelSm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Paid From</label>
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

    public function purchaseProducts1(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $allaccount = DB::table('account_types')->select('id', 'account_head_id', 'account_name', 'is_money', 'code')->where(['store_id' => $store_id, 'acctype_status' => 1])->get();
        $inventory = $allaccount->where('account_name', 'Inventory')->pluck('code')->first();
        $discount_received = $allaccount->where('account_name', 'Discount Received')->pluck('code')->first();
        productStock::insert(session('cart'));
        $total = 0;
        $supplier_id = '';
        $invoice_no = '';
        $purchase_date = '';
        foreach (session('cart') as $p) {
            $total1 = $total + ($p['buy_price'] * $p['quantity']);
            $total = $total1  + ($p['tax_value_percent'] / 100) * $total1;
            $supplier_id = $p['supplier_id'];
            $invoice_no = $p['invoice_no'];
            $purchase_date = $p['purchase_date'];
        }
        $sid = DB::table('suppliers')->where(['id' => $p['supplier_id'], 'store_id' => $store_id])->first();
        $due = $total - ($request->discount + $request->paid);
        $discount = $request->discount;
        $paid = $request->paid;
        $acc_code = Account_Types::where(['id' => $request->credit, 'store_id' => $store_id])->first()->code;

        // if ($total == ($request->due + $request->discount + $request->paid)) {

        $purchase = new Purchase();
        $purchase->description = $request->description;
        $purchase->supplier_id = $supplier_id;
        $purchase->invoice_no = $invoice_no;
        $purchase->trns_type = $request->credit;
        $purchase->total = $total;
        $purchase->due = $due;
        $purchase->discount = $discount;
        $purchase->paid = $paid;
        $purchase->purchase_date = $purchase_date;
        $purchase->purchase_status = 1; // due, etc
        $purchase->store_id = $store_id;
        $query = $purchase->save();
        // }

        $save_data = [];
        if ($total == $paid) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $inventory,
                'description' => 'Goods purchased in cash ' . $paid,
                'amount' => $paid,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => 'Goods purchased in cash ' . $paid,
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
                'account_head_id' => $inventory,
                'description' => 'Goods purchased in cash with discount ' . $discount,
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => 'Goods purchased in cash with discount ' . $discount,
                'amount' => $paid,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $discount_received,
                'description' => 'Goods purchased in cash with discount ' . $discount,
                'amount' => $discount,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);
        }

        if (($discount == 0) and ($due > 0) and ($paid > 0) and ($paid + $due == $total)) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $inventory,
                'description' => 'Goods purchased in on credit Pertialy',
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $sid->parent_id,
                'description' => 'Goods purchased on credit Pertially',
                'amount' => $due,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => '--Goods purchased on credit with some cash ' . $paid,
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
                'account_head_id' => $inventory,
                'description' => 'Goods purchased on credit',
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $sid->parent_id,
                'description' => 'Goods purchased on credit',
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
                'account_head_id' => $inventory,
                'description' => 'Goods purchased on credit with discount',
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $sid->parent_id,
                'description' => 'Goods purchased on credit with discount',
                'amount' => $due,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $discount_received,
                'description' => 'Goods purchased on credit with discount',
                'amount' => $discount,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);
        }

        if (($due > 0) and $discount > 0 and $paid > 0 and ($total == ($due + $discount + $paid))) {
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $inventory,
                'description' => '--Goods purchased on credit with discount',
                'amount' => $total,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $acc_code,
                'description' => '--Goods purchased in cash ' . $paid,
                'amount' => $paid,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $sid->parent_id,
                'description' => '--Goods purchased on credit with discount',
                'amount' => $due,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $invoice_no,
                'account_head_id' => $discount_received,
                'description' => '--Goods purchased on credit with discount',
                'amount' => $discount,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);
        }

        session()->forget('cart');
        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Purchase Successfully']);
        }
    }

    public function stockDamage(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('damage_products')->select('id')->where(['store_id' => $store_id])->get();
        session()->forget('pdtdamagecart');
        $inv = 'SD-' . date('ymd') . '-' . count($invtotal) + 1;

        $data = ['inv' => $inv];
        return view('dashboard.admin.productManagement.damageStock', $data);
    }

    public function addstockdamage(Request $request)
    {
        // $product = new Sale();
        $store_id = \Auth::guard('admin')->user()->store_id;
        $productDetails = productStock::select('product_id')->where(['id' => $request->pdtstock_id, 'store_id' => $store_id])->first();
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

            $cart = session()->get('pdtdamagecart', []);
            $cartId = md5(uniqid(rand(), true));

            if (isset($cart[$cartId])) {
                $cart[$cartId]['quantity']++;
            } else {
                $cart[$cartId] = [
                    "damage_hash_id" =>  $cartId,
                    "product_id" => $productDetails->product_id,
                    "pdtstock_id" => $request->pdtstock_id,
                    "invoice_no" => $request->invoice_no,
                    "quantity" => $request->quantity,
                    "rate" => $request->sell_price,
                    "amount" => $request->sell_price * $request->quantity,
                    "store_id" => $store_id,
                    'damage_date' => Carbon::parse($request->damage_date)->toDatetimeString(),
                    'created_at' => Carbon::parse(now())->toDatetimeString(),
                    'updated_at' => Carbon::parse(now())->toDatetimeString(),
                ];
            }
            session()->put('pdtdamagecart', $cart);
            $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
            $i = 1;
            $total = 0;
            $totalprice = 0;
            foreach (session('pdtdamagecart') as $p) {
                $total = $total + ($p['rate'] * $p['quantity']);
                $productDetails1 = DB::table('products')->select('product_title')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
                // $pdtname = $productDetails->product_title;
                $html .= "<tr>";
                $html .= "<td>" . $i++ . "</td>" . "<td>" . $productDetails1->product_title . "</td>" . "<td>" . $p['invoice_no'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['damage_hash_id'] . '" id="deletePdtBtn">X</button></td>';
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

    public function deleteDmgCart(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $cartId = $request->cartId;
        if ($cartId) {
            $cart = session()->get('pdtdamagecart');
            if (isset($cart[$cartId])) {
                unset($cart[$cartId]);
                session()->put('pdtdamagecart', $cart);
            }
            $html = "<table class='table table-bordered table-hovered'><tr><th>Sn</th><th>Product Name</th><th>Invoice No</th><th>Quantity</th><th>Rate</th><th>Amount</th><th>Action</th></tr>";
            $i = 1;
            $total = 0;
            $totalprice = 0;
            foreach (session('pdtdamagecart') as $p) {
                $total = $total + ($p['rate'] * $p['quantity']);
                $productDetails1 = DB::table('products')->select('product_title')->where(['id' => $p['product_id'], 'store_id' => $store_id])->first();
                // $pdtname = $productDetails->product_title;
                $html .= "<tr>";
                $html .= "<td>" . $i++ . "</td>" . "<td>" . $productDetails1->product_title . "</td>" . "<td>" . $p['invoice_no'] . "</td>" . "<td>" . $p['quantity'] . "</td>" . "<td>" . $p['rate'] . "</td>" . "<td>" . $p['rate'] * $p['quantity'] . '<td><button class="btn btn-sm btn-danger" data-id="' . $p['damage_hash_id'] . '" id="deletePdtBtn">X</button></td>';
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



    public function stockDamageAction(Request $request)
    {
        // dd(session('pdtdamagecart'));
        $store_id = \Auth::guard('admin')->user()->store_id;
        $allaccount = DB::table('account_types')->select('id', 'account_head_id', 'account_name', 'is_money', 'code')->where(['store_id' => $store_id, 'acctype_status' => 1])->get();
        $damage_expense = $allaccount->where('account_name', 'Damage Expense')->pluck('code')->first();
        $inventory = $allaccount->where('account_name', 'Inventory')->pluck('code')->first();
        DamageProducts::insert(session('pdtdamagecart'));

        $invoice_no = '';
        $total = 0;
        foreach (session('pdtdamagecart') as $p) {
            $invoice_no = $p['invoice_no'];
            DB::statement("update product_stocks set quantity=quantity-" . $p['quantity'] . " where store_id = '" . $store_id . "' AND id = '" . $p['pdtstock_id'] . "'");
            $total = $total + $p['amount'];
        }


        $save_data = [];

        $save_data[] = [
            'trns_id' => $invoice_no,
            'account_head_id' => $damage_expense,  //Damage Expense
            'description' => 'Goods Damaged',
            'amount' => $total,
            'direction' => 1,
            'trns_date' => date('Y-m-d'),
            'store_id' => $store_id
        ];
        $save_data[] = [
            'trns_id' => $invoice_no,
            'account_head_id' => $inventory, //Inventory
            'description' => 'Goods Damaged',
            'amount' => $total,
            'direction' => -1,
            'trns_date' => date('Y-m-d'),
            'store_id' => $store_id
        ];
        Transactions::insert($save_data);

        $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
        $datas['inv'] = $invoice_no;
        $datas['txt'] = "Damaged Products";
        $datas['damagepdts'] = DB::table('damage_products')
            ->join('products', 'products.id', '=', 'damage_products.product_id')
            ->join('product_stocks', 'product_stocks.id', '=', 'damage_products.pdtstock_id')
            ->select('products.product_title', 'damage_products.*', 'product_stocks.barcode', 'product_stocks.batch_no', 'product_stocks.serial_no', 'product_stocks.size', 'product_stocks.color')
            ->where(['damage_products.invoice_no' => $invoice_no, 'damage_products.store_id' => $store_id])
            ->get();

        $html1 = view('dashboard.admin.reports.stocks.product_damaged', $datas)->render();
        session()->forget('pdtdamagecart');
        // $invtotal = DB::table('sales')->where(['store_id' => $store_id])->get();
        // $inv = 'SI-' . date('ymd') . '-' . count($invtotal) + 1;

        if (!$save_data) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Damaged Product Entered Successfully', 'html1' => $html1]);
        }
    }
}
