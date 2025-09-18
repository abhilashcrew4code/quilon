<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ReportDownloadLog;
use App\Models\LoginLog;
use App\Models\FrontOffice;
use Response;

class ReportController extends Controller
{
    //Login Logs
    public function viewLoginLogs(Request $request)
    {
        if ($request->download != '') {
            return $this->downloadLoginLogs($request);
        }
        $search_date         = $request->search_date ? $request->search_date : date('d/m/Y') . ' - ' . date('d/m/Y');
        $entry_count         = $request->entry_count ? $request->entry_count : 15;
        $search_date_xplod     = explode(' - ', $search_date);
        $start_date         = implode('-', array_reverse(explode('/', $search_date_xplod[0])));
        $end_date             = implode('-', array_reverse(explode('/', $search_date_xplod[1])));
        $search             = array('search_date' => $search_date);

        if (Auth::user()->hasRole('super-admin')) {
            $data = LoginLog::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->orderBy('id', 'DESC')->paginate($entry_count);
        } elseif (Auth::user()->hasRole('admin')) {
            $user_arr = array();
            $super_users = User::select('id')->role('super-admin')->get();
            foreach ($super_users as $key => $value) {
                $user_arr[] = $value->id;
            }
            $data = LoginLog::whereNotIn('user_id', $user_arr)->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->orderBy('id', 'DESC')->paginate($entry_count);
        } else {
            $data = LoginLog::where('user_id', Auth::user()->id)->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->orderBy('id', 'DESC')->paginate($entry_count);
        }

        //dd($data);  
        if ($request->ajax()) {
            return view('reports.login-logs.listPagin', compact('data', 'search'));
        }
        return view('reports.login-logs.list', compact('data', 'search'));
    }

    //Download Login Logs
    public function downloadLoginLogs(Request $request)
    {
        if (auth()->user()->can('reports.login.logs.download')) {
            $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Content-type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename=LoginLogs.csv',
                'Expires'             => '0',
                'Pragma'              => 'public'
            ];

            $search_date         = $request->search_date ? $request->search_date : date('d/m/Y') . ' - ' . date('d/m/Y');
            $entry_count         = $request->entry_count ? $request->entry_count : 15;
            $search_date_xplod     = explode(' - ', $search_date);
            $start_date         = implode('-', array_reverse(explode('/', $search_date_xplod[0])));
            $end_date             = implode('-', array_reverse(explode('/', $search_date_xplod[1])));
            $search             = array('search_date' => $search_date);

            if (!Auth::user()->hasRole('super-admin')) {
                $rpt_log                 = new ReportDownloadLog();
                $rpt_log->report_name    = 'Login Logs';
                $rpt_log->start_date     = $start_date;
                $rpt_log->end_date         = $end_date;
                $rpt_log->user_id         = Auth::user()->id;
                $rpt_log->created_time     = Carbon::now();
                $rpt_log->save();
            }

            if (!Auth::user()->hasRole('super-admin')) {
                $user_arr = array();
                $super_users = User::select('id')->role('super-admin')->get();
                foreach ($super_users as $key => $value) {
                    $user_arr[] = $value->id;
                }
                $list = LoginLog::whereNotIn('user_id', $user_arr)->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->orderBy('id', 'DESC')->get();
            } else {
                $list = LoginLog::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->orderBy('id', 'DESC')->get();
            }

            if ($list) {
                //array_unshift($list, array_keys($list[0]));   
            } else {
                return redirect()->route('reports.login.logs.list')->with('error', 'No data to download');
            }

            $content[] = array('Sl No.', 'User',  'Login_IP', 'Location', 'Login_Date_Time', 'Last_Active_Time');

            $inc = 1;
            foreach ($list as $row) {

                $content[] = array(
                    $inc,
                    $row->user->name,
                    $row->ip,
                    $row->location,
                    $row->created_at,
                    $row->last_active_at,
                );
                $inc++;
            }

            if (!Auth::user()->hasRole('super-admin')) {
                $rpt_log->status = 1;
                $rpt_log->save();
            }

            $callback = function () use ($content) {
                $FH = fopen('php://output', 'w');
                foreach ($content as $row_data) {

                    $data = $row_data;
                    fputcsv($FH, $data);
                }
                fclose($FH);
            };

            return Response::stream($callback, 200, $headers);
        } else {
            return redirect()->route('reports.login.logs.list')->with('error', 'Permission Denied');
        }
    }
}
