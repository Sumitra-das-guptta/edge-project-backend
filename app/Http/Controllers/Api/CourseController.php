<?php

namespace App\Http\Controllers\Api;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function view() {
        $courses = Course::with('applicants', 'batches')->get();
        $status = 200;
        $data = compact('courses', 'status');
        return response()->json($data, 200);

    }

    public function store(Request $request) {
        // Get the JSON string from the request
        $jsonData = $request->input('formdata');
        
        // Decode the JSON data
        $formDataArray = json_decode($jsonData, true);

        // Trim whitespace from the beginning and end of each value in the array
        $trimmedDataArray = array_map('trim', $formDataArray);
        $validator = Validator::make($trimmedDataArray, [
            'name' => 'required|string',
            'trainingOrganizerUniversity' => 'required|string',
            'organizerDeptOrInstituteOrCenter' => 'required|string',
            'trainingLocation' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors'=> $validator->messages()
            ], 422);
        }
        else {
            //insert query
        $course = new Course;
        $course->name = $trimmedDataArray['name'];
        $course->description = $trimmedDataArray['description'];
        $course->trainingOrganizerUniversity = $trimmedDataArray['trainingOrganizerUniversity'];
        $course->organizerDeptOrInstituteOrCenter = $trimmedDataArray['organizerDeptOrInstituteOrCenter'];
        $course->trainingLocation = $trimmedDataArray['trainingLocation'];
        $course->save();

        if($course) {
            return response()->json([
                'status' => 200,
                'message' => 'Course Created Successfully'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ], 500);
        }

        }
        
    }

    public function destroy($id) {
        $course = Course::with('applicants', 'batches')->find($id); 
        if(!is_null($course)) {
            if($course->applicants->count() > 0) {
                return response()->json([
                    'status' => 422,
                    'message' => 'This course has registered applicants!'
                ], 422);
            }
            else if($course->batches->count() > 0) {
                return response()->json([
                    'status' => 422,
                    'message' => 'This course has multiple batches!'
                ], 422);
            }
            else {
                $course->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Successfully Deleted!'
                ], 200);
            }
        }
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Course Not Found!'
            ], 400);
        }
    }

    public function update(Request $request, $id) {
        // Get the course by ID
        $course = Course::find($id);
        
        // If the course doesn't exist, return an error response
        if (!$course) {
            return response()->json([
                'status' => 404,
                'message' => 'Course not found!'
            ], 404);
        }
        // Get the JSON string from the request
        $jsonData = $request->all();
        // Check if JSON data is null or empty
        if (empty($jsonData)) {
            return response()->json([
                'status' => 422,
                'message' => 'No data provided!'
            ], 422);
        }
        // Decode the JSON data
        // $formDataArray = json_decode($jsonData, true);
    
        // Trim whitespace from the beginning and end of each value in the array
        $trimmedDataArray = array_map('trim', $jsonData);
    
        // Validate the form data
        $validator = Validator::make($trimmedDataArray, [
            'name' => 'required|string',
            'trainingOrganizerUniversity' => 'required|string',
            'organizerDeptOrInstituteOrCenter' => 'required|string',
            'trainingLocation' => 'required|string'
        ]);
    
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
    
        // Update the course information
        $course->name = $trimmedDataArray['name'];
        $course->description = $trimmedDataArray['description'];
        $course->trainingOrganizerUniversity = $trimmedDataArray['trainingOrganizerUniversity'];
        $course->organizerDeptOrInstituteOrCenter = $trimmedDataArray['organizerDeptOrInstituteOrCenter'];
        $course->trainingLocation = $trimmedDataArray['trainingLocation'];
        $course->save();
    
        // Check if the course update was successful
        if ($course) {
            return response()->json([
                'status' => 200,
                'message' => 'Course Updated Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
    
}
