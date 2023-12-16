<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicFeeGroup;
use App\Models\Admin\EduClasses;
use App\Models\Admin\Section;
use App\Models\Admin\Subject;
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

    public function getFeegroupByAcademicYear(Request $request)
    {
        $academic_year = $request->input('academic_year');
        $feegroups = AcademicFeeGroup::where(['academic_year' => $academic_year, 'aca_group_status' => 1])->get();

        return response()->json(['feegroups' => $feegroups]);
    }

    public function getSubjectsByClass(Request $request)
    {
        $classId = $request->input('class_id');
        $versionId = $request->input('version_id');

        // Fetch subjects based on the selected class and version
        $subjects = Subject::where('class_id', $classId)
            ->where('version_id', $versionId)
            ->get();

        return response()->json(['subjects' => $subjects]);
    }
}
