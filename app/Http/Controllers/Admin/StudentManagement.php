<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduVersions;
use Illuminate\Http\Request;

class StudentManagement extends Controller
{
    public function admission()
    {
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.StudentManagement.student_admission', compact( 'versions'));
    }

    public function stdAdmission(Request $request)
    {
        dd($request);
    }
}
