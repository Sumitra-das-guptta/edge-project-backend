<?php

namespace App\Http\Controllers\API;

use App\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function requestOtp(Request $request)
    {
        // Validate request data
        // $request->validate([
        //     'email' => 'required|email|exists:users,email'
        // ]);

        // Generate OTP
        // $otp = rand(1000, 9999);

        // Update user's OTP in the database
        // User::where('email', $request->email)->update(['otp' => $otp]);
        // Validate request data
        $request->validate([
            'email' => 'required|email'
        ]);
        
        // Generate OTP
        $otp = rand(1000, 9999);

        // Check if the user with the provided email exists
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Update existing user's OTP in the database
            $user->otp = $otp;
            // $user->save();
        } else {
            // Create a new user with the provided email and save the OTP
            $user = new User();
            $user->name = $request->email;
            $user->email = $request->email;
            $user->password = 12345;
            $user->otp = $otp;
            // $user->save();
        }
        // Send OTP in email
        $mailDetails = [
            'subject' => 'Email Verification for EDGE Registration',
            'body' => $otp
        ];
        // Mail::to($request->email)->send(new \App\Mail\SendOtpMail($mailDetails));
        //     return response()->json(['message' => 'OTP sent successfully'], 200);
        try {
            $user->save();
            Mail::to($request->email)->send(new \App\Mail\SendOtpMail($mailDetails));
            return response()->json(['message' => 'OTP sent successfully'], 200);
        } catch (\Exception $e) {
            
            return response()->json(['message' => 'OTP sent failed'], 400);
        }

                

        
        
    }

    public function verifyOtp(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4'
        ]);

        // Find user by email and OTP
        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        // If user exists and OTP is correct
        if ($user) {
            // Clear OTP
            $user->update(['otp' => null]);
            $admin = Admin::where('email', $request->email)->first();
            if ($admin) {
                return response()->json(['message' => 'OTP verified successfully', 'role' => 'ADMIN'], 200);
            }
            else {
                return response()->json(['message' => 'OTP verified successfully', 'role' => 'USER'], 200);
            }

            // Generate access token (optional)
            // $accessToken = $user->createToken('authToken')->accessToken;

            
        }

        // If user doesn't exist or OTP is incorrect
        return response()->json(['message' => 'Invalid OTP'], 401);
    }
}
