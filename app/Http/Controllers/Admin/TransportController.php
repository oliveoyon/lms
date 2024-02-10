<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\EduVersions;
use App\Models\Admin\FeeCollection;
use App\Models\Admin\TransStopage;
use App\Models\Admin\TransVehicleType;
use App\Models\Admin\TrAssignStd;
use App\Models\Admin\TrRoute;
use App\Models\Admin\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransportController extends Controller
{
    public function stopagelist()
    {
        $send['stopages'] = TransStopage::all();
        return view('dashboard.admin.transport.stopage', $send);
    }

    public function addStopage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stopage_name' => 'required|string|max:255|unique:trans_stopages,stopage_name,NULL,id',
            'stopage_type' => 'required|string',
            'distance' => 'nullable|string',
            'stopage_description' => 'nullable|string',
            'stopage_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $stopage = new TransStopage();
        $stopage->stopage_hash_id = md5(uniqid(rand(), true));
        $stopage->stopage_name = $request->input('stopage_name');
        $stopage->stopage_type = $request->input('stopage_type');
        $stopage->distance = $request->input('distance');
        $stopage->stopage_description = $request->input('stopage_description');
        $stopage->stopage_status = $request->input('stopage_status');
        $query = $stopage->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Stopage added successfully', 'redirect' => 'admin/stopage-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function getStopageDetails(Request $request)
    {
        $stopage_id = $request->stopage_id;
        $stopageDetails = TransStopage::find($stopage_id);
        return response()->json(['details' => $stopageDetails]);
    }

    public function updateStopageDetails(Request $request)
    {
        // dd($request->input('stopage_name'));
        $stopage_id = $request->sid;
        $stopage = TransStopage::find($stopage_id);

        $validator = Validator::make($request->all(), [
            'stopage_name' => 'required|string|max:255|unique:trans_stopages,stopage_name,' . $request->sid . ',id',
            'stopage_type' => 'required|string',
            'distance' => 'nullable|string',
            'stopage_description' => 'nullable|string',
            'stopage_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $stopage->stopage_name = $request->input('stopage_name');
        $stopage->stopage_type = $request->input('stopage_type');
        $stopage->distance = $request->input('distance');
        $stopage->stopage_description = $request->input('stopage_description');
        $stopage->stopage_status = $request->input('stopage_status');
        $query = $stopage->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Stopage updated successfully', 'redirect' => 'admin/stopage-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteStopage(Request $request)
    {
        $stopage_id = $request->stopage_id;
        $query = TransStopage::find($stopage_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Stopage deleted successfully', 'redirect' => 'admin/stopage-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function vehicletypelist()
    {
        $send['vehicletypes'] = TransVehicleType::get();
        return view('dashboard.admin.transport.vehicletype', $send);
    }

    public function addVehicletype(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string|max:255',
            'vehicle_type_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $vehicletype = new TransVehicleType();
            $vehicletype->vehicle_type_hash_id = md5(uniqid(rand(), true));
            $vehicletype->vehicle_type = $request->input('vehicle_type');
            $vehicletype->vehicle_type_status = $request->input('vehicle_type_status');
            $vehicletype->school_id = auth()->user()->school_id;
            $query = $vehicletype->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.vehicletype_add_msg') , 'redirect'=> 'admin/vehicletype-list']);
            }
        }
    }

    public function getVehicletypeDetails(Request $request)
    {
        $vehicletype_id = $request->vehicletype_id;
        $vehicletypeDetails = TransVehicleType::find($vehicletype_id);
        return response()->json(['details' => $vehicletypeDetails]);
    }

    //UPDATE Category DETAILS
    public function updateVehicletypeDetails(Request $request)
    {
        $vehicletype_id = $request->vid;
        $vehicletype = TransVehicleType::find($vehicletype_id);

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string|max:255|unique:trans_vehicle_types,vehicle_type,' . $vehicletype_id,
            'vehicle_type_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $vehicletype->vehicle_type = $request->input('vehicle_type');
            $vehicletype->vehicle_type_status = $request->input('vehicle_type_status');
            $query = $vehicletype->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.vehicletype_edit_msg') , 'redirect'=> 'admin/vehicletype-list']);
            }
        }
    }

    public function deleteVehicletype(Request $request)
    {
        $vehicletype_id = $request->vehicletype_id;
        $query = TransVehicleType::find($vehicletype_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.vehicletype_del_msg') , 'redirect' => 'admin/vehicletype-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function vehiclelist()
    {
        $send['vehicles'] = Vehicle::with(['vtype'])->get();
        $send['vehicleTypes'] = TransVehicleType::where('vehicle_type_status', 1)->get();
        return view('dashboard.admin.transport.vehicle', $send);
    }

    public function addVehicle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_name' => 'required|string|max:255',
            'vehicle_no' => 'required|string|max:50',
            'vehicle_type_id' => 'required',
            'vehicle_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $vehicle = new Vehicle();
            $vehicle->vehicle_hash_id = md5(uniqid(rand(), true));
            $vehicle->vehicle_name = $request->input('vehicle_name');
            $vehicle->vehicle_no = $request->input('vehicle_no');
            $vehicle->vehicle_reg_no = $request->input('vehicle_reg_no');
            $vehicle->vehicle_type_id = $request->input('vehicle_type_id');
            $vehicle->vehicle_status = $request->input('vehicle_status');
            $vehicle->school_id = auth()->user()->school_id;
            $query = $vehicle->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.vehicle_add_msg') , 'redirect'=> 'admin/vehicle-list']);
            }
        }
    }

    public function getVehicleDetails(Request $request)
    {
        $vehicle_id = $request->vehicle_id;
        $vehicleDetails = Vehicle::find($vehicle_id);
        return response()->json(['details' => $vehicleDetails]);
    }

    //UPDATE Category DETAILS
    public function updateVehicleDetails(Request $request)
    {
        $vehicle_id = $request->vid;
        $vehicle = Vehicle::find($vehicle_id);

        $validator = Validator::make($request->all(), [
            'vehicle_name' => 'required|string|max:255|unique:vehicles,vehicle_name,' . $vehicle_id,
            'vehicle_no' => 'required|string|max:50',
            'vehicle_type_id' => 'required',
            'vehicle_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $vehicle->vehicle_name = $request->input('vehicle_name');
            $vehicle->vehicle_no = $request->input('vehicle_no');
            $vehicle->vehicle_reg_no = $request->input('vehicle_reg_no');
            $vehicle->vehicle_type_id = $request->input('vehicle_type_id');
            $vehicle->vehicle_status = $request->input('vehicle_status');
            $query = $vehicle->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.vehicle_edit_msg') , 'redirect'=> 'admin/vehicle-list']);
            }
        }
    }

    public function deleteVehicle(Request $request)
    {
        $vehicle_id = $request->vehicle_id;
        $query = Vehicle::find($vehicle_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.vehicle_del_msg') , 'redirect' => 'admin/vehicle-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function routelist()
    {
        $send['routes'] = TrRoute::get();
        $send['vehicles'] = Vehicle::all();
        $send['stopages'] = TransStopage::all();
        return view('dashboard.admin.transport.route', $send);
    }

    public function addRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255',
            'route_status' => 'required|in:0,1',
            'vehicle_id' => 'required|exists:vehicles,id',
            'stopage_id' => 'required|exists:trans_stopages,id',
            'pickup_time' => 'nullable|date_format:H:i',
            'drop_time' => 'nullable|date_format:H:i',
            'route_description' => 'nullable|string|max:255',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $route = new TrRoute();
            $route->route_hash_id = md5(uniqid(rand(), true));
            $route->route_name = $request->input('route_name');
            $route->route_status = $request->input('route_status');
            $route->vehicle_id = $request->input('vehicle_id');
            $route->stopage_id = $request->input('stopage_id');
            $route->pickup_time = $request->input('pickup_time');
            $route->drop_time = $request->input('drop_time');
            $route->route_description = $request->input('route_description');
            $route->school_id = auth()->user()->school_id;
            $query = $route->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.route_add_msg') , 'redirect'=> 'admin/route-list']);
            }
        }
    }

    public function getRouteDetails(Request $request)
    {
        $route_id = $request->route_id;
        $routeDetails = TrRoute::find($route_id);
        return response()->json(['details' => $routeDetails]);
    }

    //UPDATE Category DETAILS
    public function updateRouteDetails(Request $request)
    {
        $route_id = $request->vid;
        $route = TrRoute::find($route_id);

        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255|unique:edu_routes,route_name,' . $route_id,
            'route_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $route->route_name = $request->input('route_name');
            $route->route_status = $request->input('route_status');
            $query = $route->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.route_edit_msg') , 'redirect'=> 'admin/route-list']);
            }
        }
    }

    public function deleteRoute(Request $request)
    {
        $route_id = $request->route_id;
        $query = TrRoute::find($route_id);
        $query = $query->delete();


        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.route_del_msg') , 'redirect' => 'admin/route-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function assignStdTrans(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'academic_year' => 'required|integer',
                'amount' => 'required|numeric',
                'std_id' => 'required|array|min:1',
                'month' => 'required|array|min:1',
                'route_id' => 'required|numeric',
                'pickup_stopage' => 'required',
            ]);


            if ($validator->fails()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $academicYear = $request->input('academic_year');
            $versionId = $request->input('version_id');
            $classId = $request->input('class_id');
            $sectionId = $request->input('section_id');
            $stdIds = $request->input('std_id'); // Assuming std_id is an array
            $feeDesc = $request->input('fee_desc');
            $month = $request->input('month');
            $amount = $request->input('amount');

            $query = AcademicStudent::query();

            $query->when($academicYear, function ($query) use ($academicYear) {
                return $query->where('academic_students.academic_year', $academicYear);
            })
                ->when($versionId, function ($query) use ($versionId) {
                    return $query->where('academic_students.version_id', $versionId);
                })
                ->when($classId, function ($query) use ($classId) {
                    return $query->where('academic_students.class_id', $classId);
                })
                ->when($stdIds !== null, function ($query) use ($stdIds) {
                    return $query->whereIn('academic_students.std_id', $stdIds);
                });

            $result = $query->get(['std_id']);

            foreach($result as $r){

                TrAssignStd::create([
                    'tr_assign_hash_id' => 'fdfdf',
                    'std_id' => $r->std_id,
                    'route_id' => $request->route_id,
                    'pickup_stopage' => $request->pickup_stopage,
                    'pickup_stopage' => $request->pickup_stopage,
                    'drop_stopage' => 0,
                    'pickup_time' => $request->pickup_time,
                    'drop_time' => $request->input('drop_time'),
                    'academic_year' => $academicYear,
                    'tr_assign_status' => 1,
                    'school_id' => auth()->user()->school_id,
                ]);

                foreach ($month as $selectedMonth) {
                    $dueDate = Carbon::create($academicYear, $selectedMonth, 1, 0, 0, 0)->addDays(19);
                    FeeCollection::create([
                        'fee_collection_hash_id' => md5(uniqid(rand(), true)),
                        'std_id' => $r->std_id,
                        'fee_group_id' => 0,
                        'aca_feehead_id' => 0,
                        'aca_feeamount_id' => 0,
                        'academic_year' => $academicYear,
                        'payable_amount' => $amount,
                        'is_paid' => false,
                        'due_date' => $dueDate,
                        'fee_description' => 'Transport Fee',
                        'fee_collection_status' => 1,
                        'school_id' => auth()->user()->school_id,
                    ]);
                }
            }
            return response()->json(['code' => 1, 'msg' => __('language.periods_add_msg'), 'redirect' => 'admin/assign-students-transport']);
        }

        $send['versions'] = EduVersions::get()->where('version_status', 1);
        $send['routes'] = TrRoute::where('route_status', 1)->get();
        $send['stopages'] = TransStopage::where('stopage_status', 1)->get();
        return view('dashboard.admin.transport.assignStd', $send);
    }
}
