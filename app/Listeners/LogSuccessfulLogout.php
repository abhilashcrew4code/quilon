<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginLog;
use Auth;
use Request;
use Session;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $session_id = Session::getId();
        $log = LoginLog::where('user_id', Auth::user()->id)
            ->where('session_id', $session_id)
            ->orderBy('id', 'desc')
            ->first();
        if ($log) {
            $logout_time = date('Y-m-d H:i:s');
            $log->status = 'Logout';
            $log->logout_at = $logout_time;
            //$log->logout_at = $logout_time;
            $log->save();
        }
    }
}
