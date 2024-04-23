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
}
