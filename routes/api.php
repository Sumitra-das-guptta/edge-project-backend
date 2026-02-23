<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ApplicantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/* Protected Routes */
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::post('candidates', [ApplicantController::class, 'store']);
        Route::get('candidates', [ApplicantController::class, 'view']);
        Route::delete('candidates/delete/{id}', [ApplicantController::class, 'destroy']);
        Route::post('candidates/{id}', [ApplicantController::class, 'update']);

        Route::post('candidate/details', [ApplicantController::class, 'getCandidateInfoByEmail']);
        Route::get('candidates/course/{id}', [ApplicantController::class, 'getCandidateInfoByCourseId']);
        Route::get('candidates/count', [ApplicantController::class, 'getAllTableCount']);
        Route::get('courses', [CourseController::class, 'view']);
        Route::post('courses/create', [CourseController::class, 'store']);
        Route::delete('courses/delete/{id}', [CourseController::class, 'destroy']);
        Route::put('courses/{id}', [CourseController::class, 'update']);

        Route::post('batches/add-applicants', [BatchController::class, 'addApplicantsToBatch']);
        Route::post('batches/create', [BatchController::class, 'create']);
        Route::get('batches/{id}', [BatchController::class, 'getBatch']);
        Route::get('batches', [BatchController::class, 'getAllBatches']);
        Route::delete('batches/delete/{id}', [BatchController::class, 'destroy']);
        Route::put('batches/{id}', [BatchController::class, 'update']);
    });

    // User Only
    Route::middleware('role:user')->get('/user-dashboard', function () {
        return response()->json(['message' => 'Welcome User']);
    });
});





Route::post('request-otp', 'Api\AuthController@requestOtp');
Route::post('verify-otp', 'Api\AuthController@verifyOtp');
