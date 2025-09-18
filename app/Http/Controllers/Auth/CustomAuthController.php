<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            // Retrieve settings data
            $settings = Settings::where('status', 1)->first();

            if ($settings && isset($settings->json_data)) {
                $json_data = json_decode($settings->json_data, true);

                if (!empty($json_data) && isset($json_data[0]['valid_till']) && $json_data[0]['valid_till'] != '') {
                    $validTill = Carbon::createFromFormat('Y-m-d', $json_data[0]['valid_till']);
                    $currentDate = Carbon::now();

                    $daysDifference = $currentDate->diffInDays($validTill, false);

                    // Check if the user is Super Admin or Director Board
                    $user = Auth::user();
                    if ($user->hasRole('super-admin')) {
                        // Allow login even if account is expired
                    } else {
                        // For other users, check if the account is expired
                        if ($daysDifference < 0) {
                            Auth::logout();
                            return response()->json(['status' => 'error', 'message' => 'Your account has expired. Please contact the administrator.']);
                        }
                    }
                }
            } else {
            }

            // If account not expired and login is successful
            $request->session()->regenerate();
            return response()->json(['status' => 'success', 'message' => 'Login Success']);
        }

        return response()->json(['status' => 'error',  'message' => 'The provided credentials do not match our records.']);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
