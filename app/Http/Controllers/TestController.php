<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {



        for ($i=0; $i < 12; $i++) {
            $dueDate = now()->addMonths(($i - 1) * 12 / 12 + 1)->startOfMonth()->addDays(19);
            echo $dueDate.'<br>';
        }
    }
}
