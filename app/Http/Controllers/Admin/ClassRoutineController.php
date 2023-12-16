<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClassRoutineDetail;
use App\Models\Admin\ClassRoutines;
use App\Models\Admin\EduVersions;
use App\Models\Admin\Period;
use App\Models\Admin\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassRoutineController extends Controller
{
    public function createClassRoutine(){
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.RoutineManagement.create_routine', compact( 'versions'));
    }

    public function addPeriods(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|integer',
            'version_id' => 'required|exists:edu_versions,id',
            'class_id' => 'required|exists:edu_classes,id',
            'section_id' => 'required|exists:sections,id',
            'no_of_period' => 'required|integer|min:1|max:15',
            'period_name.*' => 'required|string|max:255',
            'start_time.*' => 'required|date_format:H:i',
            'end_time.*' => 'required|date_format:H:i|after:start_time.*',
        ]);

        // Additional validation to check if start time is not greater than end time
        $validator->sometimes('start_time.*', 'before_or_equal:end_time.*', function ($input) {
            return true;
        });

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Check for uniqueness in the class_routines table
        $existingRecord = ClassRoutines::where([
            'academic_year' => $request->input('academic_year'),
            'version_id' => $request->input('version_id'),
            'class_id' => $request->input('class_id'),
            'section_id' => $request->input('section_id'),
        ])->first();

        if ($existingRecord) {
            return response()->json(['code' => 0, 'error' => ['unique_combination' => 'Routine already exists for this section']]);
        }

        // Logic to save period details
        $classRoutine = new ClassRoutines();
        $classRoutine->academic_year = $request->input('academic_year');
        $classRoutine->version_id = $request->input('version_id');
        $classRoutine->class_id = $request->input('class_id');
        $classRoutine->section_id = $request->input('section_id');
        $classRoutine->save();

        for ($i = 0; $i < $request->input('no_of_period'); $i++) {
            $period = new Period();
            $period->name = $request->input('period_name')[$i];
            $period->start_time = $request->input('start_time')[$i];
            $period->end_time = $request->input('end_time')[$i];
            $period->class_routine_id = $classRoutine->id; // Assign the newly created class routine ID
            $period->save();
        }

        return response()->json(['code' => 1, 'msg' => __('language.periods_add_msg'), 'redirect' => 'admin/create-class-routines']);
    }

    public function showClassRoutine() {
        // Fetch the class routines data with related names
        $classRoutines = ClassRoutines::with(['version', 'eduClass', 'section'])->get();
        return view('dashboard.admin.RoutineManagement.show_routine', compact('classRoutines'));
    }

    public function getPeriods(Request $request)
    {
        $class_id = $request->input('class_id');
        
        // Assuming YourPeriodModel is the model for your periods table
        $periods = Period::where('class_routine_id', $class_id)->get();

        return response()->json(['periods' => $periods]);
    }

    
    public function updateClassPeriodDetails(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'period_name.*' => 'required|string|max:255',
            'start_time.*' => 'required',
            'end_time.*' => 'required|after:start_time.*',
        ]);

        // Additional validation to check if start time is not greater than end time
        $validator->sometimes('start_time.*', 'before_or_equal:end_time.*', function ($input) {
            return true;
        });

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Use a transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Delete existing periods for the given class routine ID
            Period::where('class_routine_id', $request->cid)->delete();

            // Insert new periods
            for ($i = 0; $i < count($request->input('period_name')); $i++) {
                $period = new Period();
                $period->name = $request->input('period_name')[$i];
                $period->start_time = $request->input('start_time')[$i];
                $period->end_time = $request->input('end_time')[$i];
                $period->class_routine_id = $request->cid;
                $period->save();
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['code' => 1, 'msg' => __('language.periods_update_msg'), 'redirect' => 'admin/show-class-routines']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            return response()->json(['code' => 0, 'msg' => 'Error updating class periods']);
        }
    }

    public function createRoutine(){
        $versions = EduVersions::get()->where('version_status', 1);
        return view('dashboard.admin.RoutineManagement.create_routine_action', compact( 'versions'));
    }

    public function getRoutineData(Request $request)
    {
        // Fetch data from the database based on the request parameters
        $sectionId = $request->input('section_id');
        $classId = $request->input('class_id'); 
        $versionId = $request->input('version_id');
        $academicYear = $request->input('academic_year');

        // Example: Fetch class routine details for the given section ID
        $classRoutineDetails = ClassRoutineDetail::where('class_routine_id', $sectionId)->get();

        // Fetch periods for the given section ID and class ID
        $periods = Period::whereHas('classRoutine', function ($query) use ($sectionId, $classId) {
            $query->where('section_id', $sectionId)->where('class_id', $classId);
        })->get();

        // Fetch subjects for the given class ID
        $subjects = Subject::where(['version_id' => $versionId, 'class_id' => $classId, 'academic_year' => $academicYear ])->get();

        // Fetch days of week (considering a fixed set of days)
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Prepare the HTML table
        $tableHtml = '<table class="table table-bordered">' .
            '<thead>' .
            '<tr>' .
            '<th>Sl</th>' .
            '<th>Day</th>';

        // Add table headers based on periods
        foreach ($periods as $period) {
            $tableHtml .= '<th>' . $period->name . '</th>';
        }

        $tableHtml .= '</tr>' .
            '</thead>' .
            '<tbody>';

        // Add 7 static rows based on days
        for ($i = 0; $i < 7; $i++) {
            $day = $daysOfWeek[$i % count($daysOfWeek)];

            $tableHtml .= '<tr>' .
                '<td>' . ($i + 1) . '</td>' .
                '<td>' . $day . '</td>';

            // Add dropdowns for each period and subject
            foreach ($periods as $period) {
                $tableHtml .= '<td>' .
                    '<select class="form-control" name="subjects[]" data-section="' .
                    $sectionId . '" data-day="' . $day .
                    '" data-period="' . $period->id . '">' .
                    '<option value="">Select Subject</option>';

                foreach ($subjects as $subject) {
                    $tableHtml .= '<option value="' .
                        $subject->id . '">' . $subject->subject_name . '</option>';
                }

                $tableHtml .= '</select> <input type="hidden" name="pr_id[]" value="' .
                $period->id . '">'  .
                    '</td>';
            }

            $tableHtml .= '</tr>';
        }

        $tableHtml .= '</tbody>' .
            '</table>';

        // You can format other data as needed
        $formattedData = [
            'cardTitle' => 'Routine Card',
            'tableHtml' => $tableHtml,
            // Add other data as needed
        ];

        // Return a JSON response
        return response()->json($formattedData);
    }

    public function addRoutine(Request $request){
        
        $classRoutineId = DB::table('class_routines')
        ->where('version_id', $request->input('version_id'))
        ->where('class_id', $request->input('class_id'))
        ->where('section_id', $request->input('section_id'))
        ->where('academic_year', $request->input('academic_year'))
        ->value('id');

        $periodIds = $request->input('pr_id');
        $subIds = $request->input('subjects');
        
        $periodsPerDay = count($periodIds) / 7;
        $subPerDay = count($subIds) / 7;

        $daysOfWeeks = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        // Organize period IDs into subarrays for each day using array_chunk
        $prchunk = array_chunk($periodIds, $periodsPerDay);
        $subchunk = array_chunk($subIds, $subPerDay);

        foreach($prchunk as $k=>$v){
            foreach($v as $l=>$z){
                if($subchunk[$k][$l] == NULL){$subchunk[$k][$l] = 0;}
                // echo $daysOfWeeks[$k].' '.$z.' '.$subchunk[$k][$l].'<br>';
                ClassRoutineDetail::create([
                    'class_routine_id' => $classRoutineId,
                    'day_of_week' => $daysOfWeeks[$k],
                    'period_id' => $z,
                    'subject_id' => $subchunk[$k][$l],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        // Output the result
        // echo "<pre>";
        // print_r($daysOfWeek);
        exit;
        
        
       
        return response()->json(['message' => 'Class routine details saved successfully']);
    }


    public function testing()
    {
        $data = ClassRoutineDetail::with('classRoutine', 'period', 'subject')
        ->get();

        $daysOfWeeks = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $elementsPerDay = count($data) / count($daysOfWeeks);

        $dataByDay = array_chunk($data->toArray(), $elementsPerDay);

        echo '<table border="1">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Day</th>';
        foreach($dataByDay[0] as $ld){
            echo '<td>' . ($ld['period'] ? $ld['period']['name'] : 'N/A') . '</td>';
        }
        
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($daysOfWeeks as $index => $day) {
            echo '<tr>';
            echo '<td>' . $day . '</td>';
            foreach (array_slice($dataByDay[$index], 0, $elementsPerDay) as $element) {
                echo '<td>' . ($element['subject'] ? $element['subject']['subject_name'] : 'N/A') . '</td>';
            }

            echo '</tr>';
        }


        echo '</tbody>';
        echo '</table>';
    }





}
