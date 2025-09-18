<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    //

    public function index()
    {
        $now          = Carbon::now();
        $cmonth       = $now->month;
        $chour        = $now->hour;
        if ($chour >= 18) {
            $greeting = "Good Evening.";
        } elseif ($chour >= 12) {
            $greeting = "Good Afternoon.";
        } elseif ($chour < 12) {
            $greeting = "Good Morning.";
        }
        $user = User::find(Auth::user()->id);
        $announcements = Announcement::where('status', 1)->get();
        return view('home', compact('greeting', 'user', 'announcements'));
    }
}
