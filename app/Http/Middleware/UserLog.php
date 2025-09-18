<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use DB;
use Session;
use Carbon\Carbon;

class UserLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //echo $route_name = $request->route()->getActionName();
        $exception_routes = array();
        $route_name = $request->route()->getName();

        $session_id = Session::getId();
        $login_session = session('login_session');
        if ($login_session) {
            DB::table('login_logs')->where('user_id', Auth::user()->id)
                ->where('session_id', $login_session)
                ->where('session_reset_flag', 0)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->update(['session_id' => $session_id, 'session_reset_flag' => 1]);
        }

        //dd($session_id);
        if (in_array($route_name, $exception_routes)) {
            DB::table('login_logs')->where('user_id', Auth::user()->id)
                ->where('session_id', $session_id)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->update(['last_active_at' => date('Y-m-d H:i:s')]);
            return $next($request);
        } else {
            $log = DB::table('login_logs')->where('user_id', Auth::user()->id)
                ->where('session_id', $session_id)
                ->orderBy('id', 'desc')
                ->limit(1)->get();
            // dd($log[0]->last_active_at);
            if (!($log->isEmpty())) {
                $now = Carbon::now();
                $last_seen = Carbon::parse($log[0]->last_active_at);

                $absence = $now->diffInMinutes($last_seen);
                if ($absence >= 30) {
                    DB::table('login_logs')->where('user_id', Auth::user()->id)
                        ->where('session_id', $session_id)
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->update(['type' => 'auto']);

                    auth()->guard('web')->logout();

                    // Auth::guard()->logout();
                    $request->session()->invalidate();
                    return redirect()->route('login');
                } else {
                    DB::table('login_logs')->where('user_id', Auth::user()->id)
                        ->where('session_id', $session_id)
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->update(['last_active_at' => date('Y-m-d H:i:s')]);
                }
            }
        }
        return $next($request);
    }
}
