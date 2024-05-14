<?php

namespace App\Http\Controllers\Api;

use App\Batch;
use App\Applicant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    public function addApplicantsToBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|exists:batches,batch_id',
            'applicants' => 'required|array',
            'applicants.*' => 'required|exists:applicants,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            try {
                $batch = Batch::findOrFail($request->batch_id);
                $applicants = Applicant::whereIn('id', $request->applicants)->get();

                foreach ($applicants as $applicant) {
                    $applicant->batch_id = $batch->batch_id;
                    $applicant->status = 1;
                    $applicant->save();
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Applicants added to batch successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batchName' => 'required',
            'startDate' => 'required',
            'course_id' => 'required|exists:courses,course_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $batch = new Batch();
            $batch->batchName = $request->batchName;
            $batch->startDate = $request->startDate;
            $batch->endDate = $request->endDate;
            $batch->course_id = $request->course_id;
            $batch->save();

            if ($batch) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Batch created successfully',
                    'batch' => $batch
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong!',
                ], 500);
            }
        }
    }

    public function update(Request $request, $id) {
        // Get the course by ID
        $batch = Batch::find($id);
        
        // If the course doesn't exist, return an error response
        if (!$batch) {
            return response()->json([
                'status' => 404,
                'message' => 'Batch not found!'
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
            'batchName' => 'required',
            'startDate' => 'required',
            'course_id' => 'required|exists:courses,course_id',
        ]);
    
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
    
        // Update the course information
        $batch->batchName = $trimmedDataArray['batchName'];
        $batch->startDate = $trimmedDataArray['startDate'];
        $batch->endDate = $trimmedDataArray['endDate'];
        $batch->course_id = $trimmedDataArray['course_id'];
        $batch->save();
    
        // Check if the course update was successful
        if ($batch) {
            return response()->json([
                'status' => 200,
                'message' => 'Batch Updated Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function getBatch($id)
    {
        $batch = Batch::with('course', 'applicants')->findOrFail($id);

        return response()->json(['batch' => $batch]);
    }

    public function getAllBatches(Request $request)
    {
        $batches = Batch::with('course', 'applicants')->get();

        return response()->json(['batches' => $batches]);
    }

    public function destroy($id) {
        $batch = Batch::find($id); 
        if(!is_null($batch)) {
            $batch->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Successfully Deleted!'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Batch Not Found!'
            ], 400);
        }
    }
}
