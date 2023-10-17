<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Account_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        $storeid = 102;
        $data = DB::table('account_types')->select('account_head_id', 'account_name', 'is_money', 'acctype_status', 'normal')->where('store_id', 101)->get();

        foreach ($data as $d) {
            $save_data = [
                'account_type_hash_id' => md5(uniqid(rand(), true)),
                'account_head_id' => $d->account_head_id,
                'account_name' => $d->account_name,
                'is_money' => $d->is_money,
                'store_id' => $storeid,
                'acctype_status' => $d->acctype_status,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
                'code' => Account_Types::where(['account_head_id' => $d->account_head_id])->max('code') + 1,
                'acc_type' => '',
                'normal' => $d->normal
            ];
            Account_Types::insert($save_data);
        }
    }
}
