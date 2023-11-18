<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduClasses;
use App\Models\Admin\Section;
use Illuminate\Http\Request;

class DependentController extends Controller
{
    public function getClassesByVersion(Request $request)
    {
        $versionId = $request->input('version_id');
        $classes = EduClasses::where('version_id', $versionId)->get();

        return response()->json(['classes' => $classes]);
    }
    
    public function getSectionByClass(Request $request)
    {
        $classId = $request->input('class_id');
        $versionId = $request->input('version_id');
        
        $sections = Section::where(['class_id' => $classId, 'version_id' => $versionId])->get();
        return response()->json(['sections' => $sections]);
    }
}
