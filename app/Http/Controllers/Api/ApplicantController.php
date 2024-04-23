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
        $k=preg_replace('/\s+/', '',$request->input('formdata'));
        $formDataArray = json_decode($k, true);
        // Debugging: Output the request data
        // print_r($formDataArray);
        $validator = Validator::make($formDataArray, [
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
        $jsonData = json_decode($k, true);
        // $fileName = time() . "-ws." . $request->file('image')->getClientOriginalExtension();
        $fileName = $jsonData['identityNo'] . "-" . $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('public/uploads', $fileName);
        // Decode JSON data
        // $jsonData = json_decode($request->input('formdata'), true);
        $k = trim($request->input('formdata'));
        // echo $request->file('image')->storeAs('public/uploads', $fileName);
        // $file = $request->file('file');
        // $filePath = $file->store('files');
        $applicant->courseName = $jsonData['courseName'];
        $applicant->trainingOrganizerUniversity = $jsonData['trainingOrganizerUniversity'];
        $applicant->organizerDeptOrInstituteOrCenter = $jsonData['organizerDeptOrInstituteOrCenter'];
        $applicant->candidateName = $jsonData['candidateName'];
        $applicant->fatherName = $jsonData['fatherName'];
        $applicant->motherName = $jsonData['motherName'];
        $applicant->gender = $jsonData['gender'];
        $applicant->religion = $jsonData['religion'];
        $applicant->birthDate = $jsonData['birthDate'];
        $applicant->nationality = $jsonData['nationality'];
        $applicant->image_path = $fileName;
        $applicant->presentAddressRoadNo = $jsonData['presentAddressRoadNo'];
        $applicant->presentAddressThanaName = $jsonData['presentAddressThanaName'];
        $applicant->presentAddressDistrictName = $jsonData['presentAddressDistrictName'];
        $applicant->presentAddressDivisionName = $jsonData['presentAddressDivisionName'];
        $applicant->permanentAddressRoadNo = $jsonData['permanentAddressRoadNo'];
        $applicant->permanentAddressThanaName = $jsonData['permanentAddressThanaName'];
        $applicant->permanentAddressDistrictName = $jsonData['permanentAddressDistrictName'];
        $applicant->permanentAddressDivisionName = $jsonData['permanentAddressDivisionName'];
        $applicant->mobileNumber = $jsonData['mobileNumber'];
        $applicant->gurdianMobileNumber = $jsonData['gurdianMobileNumber'];
        $applicant->levelOfEducation = $jsonData['levelOfEducation'];
        $applicant->subjectName = $jsonData['subjectName'];
        $applicant->universityName = $jsonData['universityName'];
        $applicant->departmentName = $jsonData['departmentName'];
        $applicant->trainingLocation = $jsonData['trainingLocation'];
        $applicant->linkedinProfile = $jsonData['linkedinProfile'];
        $applicant->projectRepository = $jsonData['projectRepository'];
        $applicant->freelancingProfile = $jsonData['freelancingProfile'];
        $applicant->identityNo = $jsonData['identityNo'];
        $applicant->email = $jsonData['email'];
        $applicant->course_id = $jsonData['course_id'];
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
                'message' => 'Suiccessfully Deleted!'
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
