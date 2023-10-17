<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Expenditure;
use App\Models\Admin\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenditureController extends Controller
{
    public function expenditure()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accheads = DB::table('account_types')->select('account_name', 'code')->where(['account_head_id' => 3, 'store_id' => $store_id])->get();
        $frmaccounts = DB::table('account_types')->select('account_name', 'code')->where(['is_money' => 1, 'store_id' => $store_id])->get();
        $data = ['accheads' => $accheads, 'store_id' => $store_id, 'frmaccounts' => $frmaccounts];
        return view('dashboard.admin.accountManagement.expenditure', $data);
    }

    public function expaction(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('expenditures')->where(['store_id' => $store_id])->get();
        $statement = DB::select("show table status like 'expenditures'");
        $inv = 'EXP-' . date('ymd') . '-' . count($invtotal) + 1;
        $messages = [
            'acc_head_id.required' => 'Expenditure Type is Required',
            'from_account.required' => 'Expenditure From is Required',
            'amount.required' => 'Amount is Required',
            'amount.numeric' => 'Amount Should be numeric value',
            'exp_date.required' => 'Date is Required',
            'description.required' => 'Description is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'acc_head_id' => 'required',
            'from_account' => 'required',
            'amount' => 'required|numeric',
            'exp_date' => 'required',
            'description' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $exp = new Expenditure();
            $exp->expen_hash_id =  md5(uniqid(rand(), true));
            $exp->invoice_no = $inv;
            $exp->acc_head_id = $request->acc_head_id;
            $exp->from_account = $request->from_account;
            $exp->description = $request->description;
            $exp->amount = $request->amount;
            $exp->expense_status = 1;
            $exp->expense_by = Auth::guard('admin')->user()->id;
            $exp->exp_date = Carbon::createFromFormat('m/d/Y', $request->exp_date)->format('Y-m-d');
            $exp->store_id = Auth::guard('admin')->user()->store_id;
            $query = $exp->save();

            $save_data = [];
            $save_data[] = [
                'trns_id' => $inv,
                'account_head_id' => $request->acc_head_id,
                'description' => $this->getAccName($request->acc_head_id),
                'amount' => $request->amount,
                'direction' => 1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $inv,
                'account_head_id' => $request->from_account,
                'description' => $this->getAccName($request->acc_head_id),
                'amount' => $request->amount,
                'direction' => -1,
                'trns_date' => date('Y-m-d'),
                'store_id' => $store_id
            ];
            Transactions::insert($save_data);

            // $datas['info'] = DB::table('expenditures')->where(['invoice_no' => $inv, 'store_id' => $store_id])->get();
            $datas['info'] = DB::select("SELECT (tr.amount * tr.direction * ats.normal) AS Exp, tr.trns_id, tr.trns_date, tr.description, ats.account_name, exps.description as expdesc FROM transactions tr LEFT JOIN account_types ats ON ats.code = tr.account_head_id LEFT JOIN expenditures exps ON tr.trns_id = exps.invoice_no WHERE ats.account_head_id = 3 AND tr.store_id = $store_id AND tr.trns_id = '$inv';");
            // dd($data['info']);
            $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $html1 = view('dashboard.admin.accountManagement.expenditureInvoice', $datas)->render();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Expenditure Added Successfully', 'html1' => $html1]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function journal()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accheads = DB::table('account_types')
            ->join('account_heads', 'account_heads.id', '=', 'account_types.account_head_id')
            ->where('account_types.store_id', '=', $store_id)
            ->orderBy('account_types.account_head_id')
            ->select('account_types.account_name', 'account_types.code', 'account_heads.account_head')
            ->get();
        $data = ['accheads' => $accheads, 'store_id' => $store_id];
        return view('dashboard.admin.accountManagement.journal', $data);
    }

    public function journalaction(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('transactions')->where('trns_id', 'LIKE', 'JI-%')->where(['store_id' => $store_id])->get();
        $inv = 'JI-' . date('ymd') . '-' . (count($invtotal) / 2) + 1;
        $messages = [
            'debit_account.required' => 'Debit Account is Required',
            'credit_account.required' => 'Credit Account is Required',
            'amount.required' => 'Amount is Required',
            'amount.numeric' => 'Amount Should be numeric value',
            'trns_date.required' => 'Date is Required',
            'description.required' => 'Description is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'debit_account' => 'required',
            'credit_account' => 'required',
            'amount' => 'required|numeric',
            'trns_date' => 'required',
            'description' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $trnsDate = Carbon::createFromFormat('m/d/Y', $request->trns_date)->format('Y-m-d');
            $convertDate =  Carbon::createFromFormat('Y-m-d', $trnsDate)->format('F j, Y');

            $save_data = [];
            $save_data[] = [
                'trns_id' => $inv,
                'account_head_id' => $request->debit_account,
                'description' => $request->description,
                'amount' => $request->amount,
                'direction' => 1,
                'trns_date' => $trnsDate,
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $inv,
                'account_head_id' => $request->credit_account,
                'description' => $request->description,
                'amount' => $request->amount,
                'direction' => -1,
                'trns_date' => $trnsDate,
                'store_id' => $store_id
            ];
            $query = Transactions::insert($save_data);

            $datas['info'] = [
                'particular' => $request->description,
                'debit' => $this->getAccName($request->debit_account),
                'credit' => $this->getAccName($request->credit_account),
                'amount' => $request->amount,
                'trns_date' => $convertDate,
                'trnsid' => $inv
            ];
            $datas['headMsg'] = 'Journal Transaction';
            $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $html1 = view('dashboard.admin.accountManagement.journalInvoice', $datas)->render();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Journal Transaction Added Successfully', 'html1' => $html1]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function contra()
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $accheads = DB::table('account_types')
            ->join('account_heads', 'account_heads.id', '=', 'account_types.account_head_id')
            ->where('account_types.store_id', '=', $store_id)
            ->where('account_types.is_money', '=', 1)
            ->orderBy('account_types.account_head_id')
            ->select('account_types.account_name', 'account_types.code', 'account_heads.account_head')
            ->get();
        $data = ['accheads' => $accheads, 'store_id' => $store_id];
        return view('dashboard.admin.accountManagement.contra', $data);
    }

    public function contraaction(Request $request)
    {
        $store_id = \Auth::guard('admin')->user()->store_id;
        $invtotal = DB::table('transactions')->where('trns_id', 'LIKE', 'CONTRA-%')->where(['store_id' => $store_id])->get();
        $inv = 'CONTRA-' . date('ymd') . '-' . (count($invtotal) / 2) + 1;
        $messages = [
            'debit_account.required' => 'Debit Account is Required',
            'credit_account.required' => 'Credit Account is Required',
            'amount.required' => 'Amount is Required',
            'amount.numeric' => 'Amount Should be numeric value',
            'trns_date.required' => 'Date is Required',
            'description.required' => 'Description is Required',
        ];
        $validator = \Validator::make($request->all(), [
            'debit_account' => 'required',
            'credit_account' => 'required',
            'amount' => 'required|numeric',
            'trns_date' => 'required',
            'description' => 'required',
        ], $messages);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $trnsDate = Carbon::createFromFormat('m/d/Y', $request->trns_date)->format('Y-m-d');
            $convertDate =  Carbon::createFromFormat('Y-m-d', $trnsDate)->format('F j, Y');

            $save_data = [];
            $save_data[] = [
                'trns_id' => $inv,
                'account_head_id' => $request->debit_account,
                'description' => $request->description,
                'amount' => $request->amount,
                'direction' => 1,
                'trns_date' => $trnsDate,
                'store_id' => $store_id
            ];
            $save_data[] = [
                'trns_id' => $inv,
                'account_head_id' => $request->credit_account,
                'description' => $request->description,
                'amount' => $request->amount,
                'direction' => -1,
                'trns_date' => $trnsDate,
                'store_id' => $store_id
            ];
            $query = Transactions::insert($save_data);

            $datas['info'] = [
                'particular' => $request->description,
                'debit' => $this->getAccName($request->debit_account),
                'credit' => $this->getAccName($request->credit_account),
                'amount' => $request->amount,
                'trns_date' => $convertDate,
                'trnsid' => $inv
            ];
            $datas['headMsg'] = 'Contra Transaction';
            $datas['settings'] = DB::table('general_settings')->where(['store_id' => $store_id])->first();
            $html1 = view('dashboard.admin.accountManagement.journalInvoice', $datas)->render();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'Journal Transaction Added Successfully', 'html1' => $html1]);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public static function getAccName($code)
    {
        $store_id = Auth::guard('admin')->user()->store_id;
        $name = DB::table('account_types')->where(['code' => $code, 'store_id' => $store_id])->first();
        return $name->account_name;
    }

    public static function getDesc($code = NULL)
    {
        $store_id = Auth::guard('admin')->user()->store_id;
        $name = DB::table('expenditures')->where(['invoice_no' => $code, 'store_id' => $store_id])->first();
        if ($name) {
            return $name->description;
        } else {
            return '';
        }
    }
}
