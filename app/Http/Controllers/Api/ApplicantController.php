<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use App\Batch;
use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApplicantController extends Controller
{
    public function store(Request $request) {
        // $k=preg_replace('/\s+/', '',$request->input('formdata'));
        // $formDataArray = json_decode($k, true);
        // Get the JSON string from the request
        $jsonData = $request->input('formdata');
        
        // Decode the JSON data
        $formDataArray = json_decode($jsonData, true);

        // Trim whitespace from the beginning and end of each value in the array
        $trimmedDataArray = array_map('trim', $formDataArray);
        // Debugging: Output the request data
        // print_r($formDataArray);
        $validator = Validator::make($trimmedDataArray, [
            'identityNo' => 'required|string|unique:applicants,identityNo',
            'email' => 'required|email|unique:applicants,email',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors'=> $validator->messages()
            ], 422);
        }
        else {
            //insert query
        $applicant = new Applicant;
        // $jsonData = json_decode($k, true);
        // $fileName = time() . "-ws." . $request->file('image')->getClientOriginalExtension();
        $fileName = $trimmedDataArray['identityNo'] . "-" . $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('public/uploads', $fileName);
        // Decode JSON data
        // $jsonData = json_decode($request->input('formdata'), true);
        // $k = trim($request->input('formdata'));
        // Trim whitespace from the beginning and end of each value in the array
        // $k = array_map('trim', $formDataArray);
        // echo $request->file('image')->storeAs('public/uploads', $fileName);
        // $file = $request->file('file');
        // $filePath = $file->store('files');
        $applicant->courseName = $trimmedDataArray['courseName'];
        $applicant->trainingOrganizerUniversity = $trimmedDataArray['trainingOrganizerUniversity'];
        $applicant->organizerDeptOrInstituteOrCenter = $trimmedDataArray['organizerDeptOrInstituteOrCenter'];
        $applicant->candidateName = $trimmedDataArray['candidateName'];
        $applicant->fatherName = $trimmedDataArray['fatherName'];
        $applicant->motherName = $trimmedDataArray['motherName'];
        $applicant->gender = $trimmedDataArray['gender'];
        $applicant->religion = $trimmedDataArray['religion'];
        $applicant->birthDate = $trimmedDataArray['birthDate'];
        $applicant->nationality = $trimmedDataArray['nationality'];
        $applicant->image_path = $fileName;
        $applicant->presentAddressRoadNo = $trimmedDataArray['presentAddressRoadNo'];
        $applicant->presentAddressThanaName = $trimmedDataArray['presentAddressThanaName'];
        $applicant->presentAddressDistrictName = $trimmedDataArray['presentAddressDistrictName'];
        $applicant->presentAddressDivisionName = $trimmedDataArray['presentAddressDivisionName'];
        $applicant->permanentAddressRoadNo = $trimmedDataArray['permanentAddressRoadNo'];
        $applicant->permanentAddressThanaName = $trimmedDataArray['permanentAddressThanaName'];
        $applicant->permanentAddressDistrictName = $trimmedDataArray['permanentAddressDistrictName'];
        $applicant->permanentAddressDivisionName = $trimmedDataArray['permanentAddressDivisionName'];
        $applicant->mobileNumber = $trimmedDataArray['mobileNumber'];
        $applicant->gurdianMobileNumber = $trimmedDataArray['gurdianMobileNumber'];
        $applicant->levelOfEducation = $trimmedDataArray['levelOfEducation'];
        $applicant->subjectName = $trimmedDataArray['subjectName'];
        $applicant->universityName = $trimmedDataArray['universityName'];
        $applicant->departmentName = $trimmedDataArray['departmentName'];
        $applicant->trainingLocation = $trimmedDataArray['trainingLocation'];
        $applicant->linkedinProfile = $trimmedDataArray['linkedinProfile'];
        $applicant->projectRepository = $trimmedDataArray['projectRepository'];
        $applicant->freelancingProfile = $trimmedDataArray['freelancingProfile'];
        $applicant->identityNo = $trimmedDataArray['identityNo'];
        $applicant->email = $trimmedDataArray['email'];
        $applicant->course_id = $trimmedDataArray['course_id'];
        $applicant->save();

        if($applicant) {
            return response()->json([
                'status' => 200,
                'message' => 'Applicant Created Successfully'
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

    public function update(Request $request, $id) {
        // Get the Applicant by ID
        $applicant = Applicant::find($id);
        
        // If the Applicant doesn't exist, return an error response
        if (!$applicant) {
            return response()->json([
                'status' => 404,
                'message' => 'Applicant not found!'
            ], 404);
        }
        // Get the JSON string from the request
        $jsonData = $request->input('formdata');
        
        // Check if JSON data is null or empty
        if (empty($jsonData)) {
            return response()->json([
                'status' => 422,
                'message' => 'No data provided!'
            ], 422);
        }
        // Decode the JSON data
        $formDataArray = json_decode($jsonData, true);

        // Trim whitespace from the beginning and end of each value in the array
        $trimmedDataArray = array_map('trim', $formDataArray);
    
        // Validate the form data
        $validator = Validator::make($trimmedDataArray, [
            'identityNo' => 'required|string|unique:applicants,identityNo,'.$id,
            'email' => 'required|email|unique:applicants,email,'.$id,
        ]);
    
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
    
        // Update the applicant information
        if($request->hasFile('image')) {
            $fileName = $trimmedDataArray['identityNo'] . "-" . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('public/uploads', $fileName);
            $applicant->image_path = $fileName;
        }
        if((int)$applicant['course_id'] !== (int)$trimmedDataArray['course_id']) {
            $applicant->course_id = $trimmedDataArray['course_id'];
            $applicant->batch_id = null;
        }
        $applicant->courseName = $trimmedDataArray['courseName'];
        $applicant->trainingOrganizerUniversity = $trimmedDataArray['trainingOrganizerUniversity'];
        $applicant->organizerDeptOrInstituteOrCenter = $trimmedDataArray['organizerDeptOrInstituteOrCenter'];
        $applicant->candidateName = $trimmedDataArray['candidateName'];
        $applicant->fatherName = $trimmedDataArray['fatherName'];
        $applicant->motherName = $trimmedDataArray['motherName'];
        $applicant->gender = $trimmedDataArray['gender'];
        $applicant->religion = $trimmedDataArray['religion'];
        $applicant->birthDate = $trimmedDataArray['birthDate'];
        $applicant->nationality = $trimmedDataArray['nationality'];
        $applicant->presentAddressRoadNo = $trimmedDataArray['presentAddressRoadNo'];
        $applicant->presentAddressThanaName = $trimmedDataArray['presentAddressThanaName'];
        $applicant->presentAddressDistrictName = $trimmedDataArray['presentAddressDistrictName'];
        $applicant->presentAddressDivisionName = $trimmedDataArray['presentAddressDivisionName'];
        $applicant->permanentAddressRoadNo = $trimmedDataArray['permanentAddressRoadNo'];
        $applicant->permanentAddressThanaName = $trimmedDataArray['permanentAddressThanaName'];
        $applicant->permanentAddressDistrictName = $trimmedDataArray['permanentAddressDistrictName'];
        $applicant->permanentAddressDivisionName = $trimmedDataArray['permanentAddressDivisionName'];
        $applicant->mobileNumber = $trimmedDataArray['mobileNumber'];
        $applicant->gurdianMobileNumber = $trimmedDataArray['gurdianMobileNumber'];
        $applicant->levelOfEducation = $trimmedDataArray['levelOfEducation'];
        $applicant->subjectName = $trimmedDataArray['subjectName'];
        $applicant->universityName = $trimmedDataArray['universityName'];
        $applicant->departmentName = $trimmedDataArray['departmentName'];
        $applicant->trainingLocation = $trimmedDataArray['trainingLocation'];
        $applicant->linkedinProfile = $trimmedDataArray['linkedinProfile'];
        $applicant->projectRepository = $trimmedDataArray['projectRepository'];
        $applicant->freelancingProfile = $trimmedDataArray['freelancingProfile'];
        $applicant->identityNo = $trimmedDataArray['identityNo'];
        $applicant->email = $trimmedDataArray['email'];
        
        // $applicant->batch_id = null;
        // Save the updated applicant data
        $applicant->save();
    
        // Check if the applicant update was successful
        if ($applicant) {
            return response()->json([
                'status' => 200,
                'message' => 'Applicant Updated Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ], 500);
        }
    
        
    }

    public function view() {
        $applicants = Applicant::with('course')->get();
        $status = 200;
        $data = compact('applicants', 'status');
        return response()->json($data, 200);

    }
    
    public function getCandidateInfoByEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors'=> $validator->messages()
            ], 422);
        }
        else {
            // Check if the user with the provided email exists
            $user = Applicant::where('email', $request->input('email'))->first();

            if($user) {
                $status = 200;
                $data = compact('user', 'status');
                return response()->json($data, 200);
            }
            else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Not Found!'
                ], 400);
            }
        }
    }

    public function destroy($id) {
        $applicant = Applicant::find($id); 
        if(!is_null($applicant)) {
            $applicant->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Successfully Deleted!'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Applicant Not Found!'
            ], 400);
        }
    }

    public function upload(Request $request) {
        $fileName = time() . "-ws." . $request->file('image')->getClientOriginalExtension();
        echo $request->file('image')->storeAs('public/uploads', $fileName);
    }

    public function getAllTableCount() {
        $applicants = Applicant::count();
        $batches = Batch::count();
        $courses = Course::count();

        return response()->json([
            'applicants' => $applicants,
            'batches' => $batches,
            'courses' => $courses
        ], 200);
    }
}
