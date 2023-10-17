<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use App\Models\Admin\Category;
use App\Models\Admin\Location;
use App\Models\Admin\Unit;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\Product;
use App\Models\Admin\productStock;
use App\Models\Admin\Sale;
use App\Models\Admin\SaleProduct;
use App\Models\Admin\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;
use Datatables;

class ProductManagementController extends Controller
{

    ######    Category Management Starts    ######

    public function sale()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $customers = DB::table('customers')->where(['customer_status' => 1, 'store_id' => $store_id])->get();
        $accounts = DB::table('account_types')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $results = DB::table('product_stocks as ps')
            ->select('ps.*', 'p.product_title', 'u.unit_name')
            ->join('products as p', 'ps.product_id', '=', 'p.id')
            ->join('units as u', 'p.unit_id', '=', 'u.id')
            ->where('ps.store_id', '=', $store_id)
            ->where('ps.quantity', '>', '0')
            // ->whereDate('ps.expired_date', '>', now())
            ->get();
        $data['pdts'] = $results;
        $data['customers'] = $customers;
        $data['accounts'] = $accounts;
        return view('dashboard.admin.productManagement.salecontent', $data);
    }

    public function save(Request $request)
    {
        // dd($request);
        $array = json_decode($request->cart_items, true);

        if (empty($array)) {
            return response()->json(['code' => 0, 'msg' => 'Cart is empty!']);
        }

        $store_id = \Auth::guard('admin')->user()->store_id;
        $allaccount = DB::table('account_types')->select('id', 'account_head_id', 'account_name', 'is_money', 'code')->where(['store_id' => $store_id, 'acctype_status' => 1])->get();
        $sales_revenue = $allaccount->where('account_name', 'Sales Revenue')->pluck('code')->first();
        $discount_allowed = $allaccount->where('account_name', 'Discount Allowed')->pluck('code')->first();
        $invtotal = DB::table('sales')->where(['store_id' => $store_id])->get();
        $invoice_no = 'SI-' . date('ymd') . '-' . count($invtotal) + 1;
        $total = 0;
        $discount = $request->discount;
        $paid = $request->paid;

        $customer_id = $request->customer_ids;
        $description = '';

        $acc_code1 = Account_Types::where(['id' => $request->credit])->first();
        $acc_code = $acc_code1->code;
        $check_pending = 0;
        if ($acc_code1->account_name == "Bank Cheque") {
            $acc_code = $customer_id;
            $paid = 0;
            $due = $total - $discount;
            $check_pending = 1;
            $description = $request->chequedetail1;
        }

        foreach ($array as $a) {
            $total = $total + $a['total'];
            $pdt_id = productStock::select('product_id')->where('id', $a['id'])->first();
            $insert = [
                "sale_hash_id" =>  md5(uniqid(rand(), true)),
                "customer_id" => $customer_id,
                "product_id" => $pdt_id->product_id,
                "pdtstock_id" => $a['id'],
                "invoice_no" => $invoice_no,
                "quantity" => $a['qty'],
                "rate" => $a['price'],
                "sale_by" => \Auth::guard('admin')->user()->id,
                "store_id" => $store_id,
                'created_at' => Carbon::parse(now())->toDatetimeString(),
                'updated_at' => Carbon::parse(now())->toDatetimeString(),
            ];
            SaleProduct::insert($insert);
            DB::statement("update product_stocks set quantity=quantity-" . $a['qty'] . " where store_id = '" . $store_id . "' AND id = '" . $a['id'] . "'");
        }
        $due = $total - ($discount + $paid);

        $sales = new Sale();

        $sales->customer_id = $customer_id;
        $sales->invoice_no = $invoice_no;
        $sales->trns_type = $acc_code1->account_name;
        $sales->description = $description;
        $sales->total = $total;
        $sales->due = $due;
        $sales->discount = $discount;
        $sales->paid = $paid;
        $sales->sale_status = 1; // due, etc
        $sales->check_pending = $check_pending;
        $sales->store_id = $store_id;
        $query = $sales->save();


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
        $invtotal = DB::table('sales')->where(['store_id' => $store_id])->get();
        $inv = 'SI-' . date('ymd') . '-' . count($invtotal) + 1;

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Product Sold Successfully', 'html1' => $html1, 'inv' => $inv]);
        }
    }

    public function search(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $string = $request->input('query');
        // Perform search based on query and get results
        $results = DB::table('product_stocks as ps')
            ->select('ps.*', 'p.product_title')
            ->join('products as p', 'ps.product_id', '=', 'p.id')
            ->where(function ($query) use ($string) {
                $query->where('p.product_title', 'like', '%' . $string . '%')
                    ->orWhere('ps.barcode', '=', $string);
            })
            ->where('ps.store_id', '=', $store_id)
            ->where('ps.quantity', '>', '0')
            // ->whereDate('ps.expired_date', '>', now())
            ->get();

        return response()->json($results);
    }


    public function index()
    {
        return view('dashboard.admin.productManagement.category-list');
    }

    public function addCategory(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'category_name.required' => 'Category Name is Required',
            'category_name.unique' => 'Category Name Already Exists',
            // 'category_img.required' => 'Category Image is Required',
            'category_img.image' => 'Category Image muste be an Image',
        ];
        $validator = \Validator::make($request->all(), [
            'category_name' => [
                'required',
                Rule::unique('categories')->where(function ($query) use ($store_id) {
                    return $query->where(['store_id' => $store_id]);
                }),
            ],
            'category_img' => 'image',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $file_name = '';
            if ($request->file('category_img')) {
                $path = 'images/categories/';
                $file = $request->file('category_img');
                $file_name = time() . '_' . $file->getClientOriginalName();
                //$upload = $file->storeAs($path, $file_name);
                $upload = $file->storeAs($path, $file_name, 'public');
            }
            $category = new Category();
            $category->category_hash_id =  md5(uniqid(rand(), true));
            $category->category_name = $request->category_name;
            $category->store_id = \Auth::guard('admin')->user()->store_id;
            $category->category_img = $file_name;
            $category->category_status = 1;
            $query = $category->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Category has been successfully saved']);
            }
        }
    }

    // GET ALL Category  /storage/images/categories/{{$item->product_image}}
    public function getCategoriesList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $categories = Category::select('id', 'category_name', 'category_img', 'category_status')->where('store_id', $store_id)->get();
        return datatables()::of($categories)
            ->addIndexColumn()
            ->addColumn('img', function ($row) {
                $path = 'images/categories/';
                $img_path = $path . $row->category_img;
                if ($row->category_img != null && \Storage::disk('public')->exists($img_path)) {
                    return '<img src="/storage/images/categories/' . $row['category_img'] . '" style="max-height:30px;">';
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($row) {
                if ($row['category_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editCategoryBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteCategoryBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status', 'img'])
            ->make(true);
    }


    //GET Category DETAILS
    public function getCategoryDetails(Request $request)
    {
        $category_id = $request->category_id;
        $categoryDetails = Category::find($category_id);
        return response()->json(['details' => $categoryDetails]);
    }


    //UPDATE Category DETAILS
    public function updateCategoryDetails(Request $request)
    {
        $category_id = $request->cid;
        $category = Category::find($category_id);
        $path = 'images/categories/';
        $store_id = \Auth::guard('admin')->user()->store_id;
        $file_name = $category->category_img;

        $messages = [
            'category_name.required' => 'Category Name is Required',
            'category_name.unique' => 'Category Name Already Exists',
            // 'category_img.required' => 'Category Image is Required',
            'category_img_update.image' => 'Category Image muste be an Image',
        ];
        $validator = \Validator::make($request->all(), [
            'category_name' => 'required|unique:categories,category_name,' . $category_id,
            'category_img_update' => 'image',

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            if ($request->hasFile('category_img_update')) {
                $file_path = $path . $category->category_img;
                if ($category->category_img != null && \Storage::disk('public')->exists($file_path)) {
                    \Storage::disk('public')->delete($file_path);
                }
                $file = $request->file('category_img_update');
                $file_name = time() . '_' . $file->getClientOriginalName();
                //$upload = $file->storeAs($path, $file_name);
                $upload = $file->storeAs($path, $file_name, 'public');
            }


            $category->category_name = $request->category_name;
            $category->category_img = $file_name;
            $category->category_status = $request->category_status;
            $query = $category->save();
            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Category Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Category RECORD
    public function deleteCategory(Request $request)
    {
        $category_id = $request->category_id;
        $query = Category::find($category_id);

        $path = 'images/categories/';
        $img_path = $path . $query->category_img;
        if ($query->category_img != null && \Storage::disk('public')->exists($img_path)) {
            \Storage::disk('public')->delete($img_path);
        }
        $query1 = $query->delete();

        // ->delete()

        if ($query1) {
            return response()->json(['code' => 1, 'msg' => 'Category has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    ######    Unit Management Starts    ######


    public function unitlist()
    {
        return view('dashboard.admin.productManagement.units');
    }

    public function addUnit(Request $request)
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

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Unit Added Successfully']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // GET ALL Unit
    public function getUnitsList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $units = Unit::select('id', 'unit_name', 'unit_status')->where('store_id', $store_id)->get();
        //$Categorys = Edu_Category::all();
        return datatables()::of($units)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['unit_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editUnitBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteUnitBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Unit DETAILS
    public function getUnitDetails(Request $request)
    {
        $unit_id = $request->unit_id;
        $unitDetails = Unit::find($unit_id);
        return response()->json(['details' => $unitDetails]);
    }


    //UPDATE Unit DETAILS
    public function updateUnitDetails(Request $request)
    {
        $unit_id = $request->uid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'unit_name.required' => 'Unit Name is Required',
            'unit_name.unique' => 'Unit Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'unit_name' => 'required|unique:units,unit_name,' . $unit_id,

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $unit = Unit::find($unit_id);
            $unit->unit_name = $request->unit_name;
            $unit->unit_status = $request->unit_status;
            $query = $unit->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Unit Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Unit RECORD
    public function deleteUnit(Request $request)
    {
        $unit_id = $request->unit_id;
        $query = Unit::find($unit_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Unit has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    ######    Manufacturer Management Starts    ######


    public function manufacturerlist()
    {
        return view('dashboard.admin.productManagement.manufacturers');
    }

    public function addManufacturer(Request $request)
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

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Manufacturer Added Successfully']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // GET ALL Manufacturer
    public function getManufacturersList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $manufacturers = Manufacturer::select('id', 'manufacturer_name', 'manufacturer_status')->where('store_id', $store_id)->get();
        //$Categorys = Edu_Category::all();
        return datatables()::of($manufacturers)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['manufacturer_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editManufacturerBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteManufacturerBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Manufacturer DETAILS
    public function getManufacturerDetails(Request $request)
    {
        $manufacturer_id = $request->manufacturer_id;
        $manufacturerDetails = Manufacturer::find($manufacturer_id);
        return response()->json(['details' => $manufacturerDetails]);
    }


    //UPDATE Manufacturer DETAILS
    public function updateManufacturerDetails(Request $request)
    {
        $manufacturer_id = $request->uid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'manufacturer_name.required' => 'Manufacturer Name is Required',
            'manufacturer_name.unique' => 'Manufacturer Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'manufacturer_name' => 'required|unique:manufacturers,manufacturer_name,' . $manufacturer_id,

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $manufacturer = Manufacturer::find($manufacturer_id);
            $manufacturer->manufacturer_name = $request->manufacturer_name;
            $manufacturer->manufacturer_status = $request->manufacturer_status;
            $query = $manufacturer->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Manufacturer Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Manufacturer RECORD
    public function deleteManufacturer(Request $request)
    {
        $manufacturer_id = $request->manufacturer_id;
        $query = Manufacturer::find($manufacturer_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Manufacturer has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    ######    Location Management Starts    ######


    public function locationlist()
    {
        return view('dashboard.admin.productManagement.locations');
    }

    public function addShelf(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'shelf_name.required' => 'Shelf Name is Required',
            'shelf_name.unique' => 'Shelf Name Already Exists',
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

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Shelf Added Successfully']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // GET ALL Shelf
    public function getShelfsList(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $shelfs = Location::select('id', 'shelf_name', 'shelf_status')->where('store_id', $store_id)->get();
        //$Categorys = Edu_Category::all();
        return datatables()::of($shelfs)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['shelf_status'] == 1) {
                    return "Active";
                } else {
                    return "InActive";
                }
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editShelfBtn">Update</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteShelfBtn">Delete</button>
                        </div>';
            })


            ->rawColumns(['actions', 'status'])
            ->make(true);
    }


    //GET Shelf DETAILS
    public function getShelfDetails(Request $request)
    {
        $shelf_id = $request->shelf_id;
        $shelfDetails = Location::find($shelf_id);
        return response()->json(['details' => $shelfDetails]);
    }


    //UPDATE Shelf DETAILS
    public function updateShelfDetails(Request $request)
    {
        $shelf_id = $request->sid;

        $store_id = \Auth::guard('admin')->user()->store_id;
        $messages = [
            'shelf_name.required' => 'Shelf Name is Required',
            'shelf_name.unique' => 'Shelf Name Already Exists',
        ];
        $validator = \Validator::make($request->all(), [
            'shelf_name' => 'required|unique:locations,shelf_name,' . $shelf_id,

        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $shelf = Location::find($shelf_id);
            $shelf->shelf_name = $request->shelf_name;
            $shelf->shelf_status = $request->shelf_status;
            $query = $shelf->save();



            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Shelf Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    // DELETE Shelf RECORD
    public function deleteShelf(Request $request)
    {
        $shelf_id = $request->shelf_id;
        $query = Location::find($shelf_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Shelf has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
