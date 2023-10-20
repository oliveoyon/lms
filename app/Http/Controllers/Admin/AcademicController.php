<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EduClasses;
use App\Models\Admin\EduVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicController extends Controller
{
    public function versionlist()
    {
        $send['versions'] = EduVersions::get();
        return view('dashboard.admin.academic.version', $send);
    }
    
    public function addVersion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version_name' => 'required|string|max:255',
            'version_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $version = new EduVersions();
            $version->version_hash_id = md5(uniqid(rand(), true));
            $version->version_name = $request->input('version_name');
            $version->version_status = $request->input('version_status');
            $query = $version->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Version has been successfully saved', 'redirect'=> 'admin/version-list']);
            }
        }
    }

    public function getVersionDetails(Request $request)
    {
        $version_id = $request->version_id;
        $versionDetails = EduVersions::find($version_id);
        return response()->json(['details' => $versionDetails]);
    }

    //UPDATE Category DETAILS
    public function updateVersionDetails(Request $request)
    {
        $version_id = $request->vid;
        $version = EduVersions::find($version_id);

        $validator = Validator::make($request->all(), [
            'version_name' => 'required|string|max:255|unique:edu_versions,version_name,' . $version_id,
            'version_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $version->version_name = $request->input('version_name');
            $version->version_status = $request->input('version_status');
            $query = $version->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Version has been successfully saved', 'redirect'=> 'admin/version-list']);
            }
        }
    }

    public function deleteVersion(Request $request)
    {
        $version_id = $request->version_id;
        $query = EduVersions::find($version_id);
        $query = $query->delete();

        
        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Version has been deleted from database', 'redirect' => 'admin/version-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function classlist()
{
    $send['classes'] = EduClasses::get();
    $send['versions'] = EduVersions::get();
    return view('dashboard.admin.academic.class', $send);
}

public function addClass(Request $request)
{
    $validator = Validator::make($request->all(), [
        'class_name' => 'required|string|max:50|unique:edu_classes,class_name,NULL,id,version_id,' . $request->input('version_id'),
        'class_numeric' => 'required|integer|unique:edu_classes,class_name,NULL,id,version_id,' . $request->input('version_id'),
        'class_status' => 'required',
        'version_id' => 'required|exists:edu_versions,id', // Make sure the version exists
    ]);

    if ($validator->fails()) {
        return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
    }

    $class = new EduClasses();
    $class->class_hash_id = md5(uniqid(rand(), true));
    $class->class_name = $request->input('class_name');
    $class->class_numeric = $request->input('class_numeric');
    $class->class_status = $request->input('class_status');
    $class->version_id = $request->input('version_id'); // Assign the version_id
    $query = $class->save();

    if ($query) {
        return response()->json(['code' => 1, 'msg' => 'Class has been successfully saved', 'redirect' => 'admin/class-list']);
    } else {
        return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    }
}

public function getClassDetails(Request $request)
{
    $class_id = $request->class_id;
    $classDetails = EduClasses::find($class_id);
    return response()->json(['details' => $classDetails]);
}

public function updateClassDetails(Request $request)
{
    // dd($request);
    $class_id = $request->cid;
    $class = EduClasses::find($class_id);

    

    $validator = Validator::make($request->all(), [
        'class_name' => 'required|string|max:255|unique:edu_classes,class_name,' . $class_id,
        'class_numeric' => 'required|integer',
        'class_status' => 'required',
        'version_id' => 'required|exists:edu_versions,id', // Make sure the version exists
    ]);

    if ($validator->fails()) {
        return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
    }

    $class->class_name = $request->input('class_name');
    $class->class_numeric = $request->input('class_numeric');
    $class->class_status = $request->input('class_status');
    $class->version_id = $request->input('version_id'); // Update the version_id
    $query = $class->save();

    if ($query) {
        return response()->json(['code' => 1, 'msg' => 'Class has been successfully updated', 'redirect' => 'admin/class-list']);
    } else {
        return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    }
}

public function deleteClass(Request $request)
{
    $class_id = $request->class_id;
    $query = EduClasses::find($class_id);
    $query = $query->delete();

    if ($query) {
        return response()->json(['code' => 1, 'msg' => 'Class has been deleted from the database', 'redirect' => 'admin/class-list']);
    } else {
        return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    }
}

}
