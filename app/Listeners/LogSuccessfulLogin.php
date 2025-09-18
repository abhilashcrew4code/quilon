<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginLog;
use Session;
use Auth;
use Torann\GeoIP\Facades\GeoIP;
use Stevebauman\Location\Facades\Location;

class LogSuccessfulLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        session(['login_session' => '']);

        $userAgent =  request()->header('User-Agent');

        $browser = $this->getBrowser($userAgent);
        $platform = $this->getPlatform($userAgent);
        $device = $this->getDevice($userAgent);
        $os = $this->getOS($userAgent);

        $ipAddress =  request()->ip();
        $location = GeoIP::getLocation($ipAddress);
        $country = $location->country;
        $city = $location->city;

        $session_id             = Session::getId();
        $position               = Location::get();

        $log                    = new LoginLog();

        if (isset($location)) {
            $log->ip            = $ipAddress;
            $log->location      = $location ? $country . ' - ' . $city : '';
        }

        $log->user_id           = Auth::user()->id;
        $log->type              = '';
        $log->status            = 'Login';
        $log->device_model      = $device;
        $log->browser_name      = $browser;
        $log->platform_name     = $os;
        $log->location_json     = json_encode($position);
        $log->session_id        = $session_id;
        $log->last_active_at    = date('Y-m-d H:i:s');
        $log->save();

        session(['login_session' => $session_id]);
    }

    private function getBrowser($userAgent)
    {
        $browsers = [
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Edge' => 'Edg',
            'IE' => 'Trident',
        ];

        foreach ($browsers as $browserName => $browserPattern) {
            if (stripos($userAgent, $browserPattern) !== false) {
                return $browserName;
            }
        }

        return 'Unknown Browser';
    }

    private function getPlatform($userAgent)
    {

        return 'Platform Information';
    }

    private function getDevice($userAgent)
    {
        $deviceModels = [
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Samsung' => 'SAMSUNG',
            'Pixel' => 'Pixel',
        ];

        foreach ($deviceModels as $modelName => $modelPattern) {
            if (stripos($userAgent, $modelPattern) !== false) {
                return $modelName;
            }
        }

        return 'Unknown Model';
    }

    private function getOS($userAgent)
    {

        $operatingSystems = [
            'Windows' => 'Windows',
            'Mac OS X' => 'Macintosh',
            'iOS' => 'iPhone|iPad|iPod',
            'Android' => 'Android',
            'Linux' => 'Linux',
        ];

        foreach ($operatingSystems as $osName => $osPattern) {
            if (preg_match('/' . $osPattern . '/i', $userAgent)) {
                return $osName;
            }
        }

        return 'Unknown OS';
    }
}
