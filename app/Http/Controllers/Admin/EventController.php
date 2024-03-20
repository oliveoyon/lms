<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Event;
use App\Models\Admin\EventType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    
    public function eventList()
    {
        $today = Carbon::now()->format('Y-m-d');
        $events = Event::with('event_type')->where('start_date', '>=', $today)->get();
        $eventTypes = EventType::where('status', 1)->get();
        return view('dashboard.admin.events.index', ['events' => $events, 'eventTypes' => $eventTypes]);
    }

    public function addEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_title' => 'required|string|max:200',
            'event_description' => 'required|string',
            'event_type_id' => 'required|exists:event_types,id',
            'upload' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf', // Adjust the allowed file types accordingly
            'start_date' => 'required', // Assuming the date format, adjust accordingly
            'end_date' => 'required|after:start_date', // Ensure end date is after start date
            'event_status' => 'required|integer',
        ]);
        

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $color = EventType::where('id', $request->input('event_type_id'))->first();

        $event = new Event();
        $event->event_hash_id = md5(uniqid(rand(), true));
        $event->event_title = $request->input('event_title');
        $event->event_description = $request->input('event_description');
        $event->event_type_id = $request->input('event_type_id');
        $event->start_date = $request->input('start_date');
        $event->end_date = $request->input('end_date');
        $event->url = 'fdfdf';
        $event->color = $color->color;
        $event->upload = '';
        $event->event_status = $request->input('event_status');
        $event->school_id = auth()->user()->school_id;

        // Handle file upload
        // Handle file upload for edit
        if ($request->hasFile('upload')) {
            // Delete the existing file if any
            if ($event->upload) {
                Storage::delete('uploads/events/' . basename($event->upload));
            }

            $path = 'uploads/events/';
            $file = $request->file('upload');
            $fileExtension = $file->getClientOriginalExtension(); // Get the file extension
            $file_name = time() . '.' . $fileExtension;
            $file->storeAs($path, $file_name);

            // Update the file path in the database
            $event->upload = $file_name;
        }


        $query = $event->save();

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Event added successfully', 'redirect' => 'admin/event-list']);
        }
    }


    public function getEventDetails(Request $request)
    {
        $event_id = $request->event_id;
        $eventDetails = Event::find($event_id);
        return response()->json(['details' => $eventDetails]);
    }

    public function updateEventDetails(Request $request)
    {
        $event_id = $request->event_id;
        $event = Event::find($event_id);

        $validator = Validator::make($request->all(), [
            'event_title' => 'required|string|max:200',
            'event_description' => 'required|string',
            'event_type_id' => 'required|exists:event_types,id',
            'upload' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf', // Adjust the allowed file types accordingly
            'start_date' => 'required', // Assuming the date format, adjust accordingly
            'end_date' => 'required|after:start_date', // Ensure end date is after start date
            'event_status' => 'required|integer',
        ]);


        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            // Update your model attributes
            $event->event_title = $request->input('event_title');
            $event->event_description = $request->input('event_description');
            $event->event_type_id = $request->input('event_type_id');
            $event->start_date = $request->input('start_date');
            $event->end_date = $request->input('end_date');
            $event->event_status = $request->input('event_status');

            // Handle file upload
            if ($request->hasFile('upload')) {
                // Delete existing file if any
                if ($event->upload) {
                    Storage::delete($event->upload);
                }

                // Upload the new file
                $event->upload = $request->file('upload')->store('uploads/events', 'public');
            }

            // Save the changes
            $query = $event->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Event updated successfully', 'redirect' => 'admin/event-list']);
            }
        }
    }


    public function deleteEvent(Request $request)
    {
        $event_id = $request->event_id;
        $event = Event::find($event_id);

        // Delete the associated image
        if ($event->upload) {
            Storage::delete('uploads/events/' . basename($event->upload));
        }

        $query = $event->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Event deleted successfully', 'redirect' => 'admin/event-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }


}
