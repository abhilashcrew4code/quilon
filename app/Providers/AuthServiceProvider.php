<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // $settings = Settings::where('status', 1)->first();

        // if (isset($settings->json_data)) {
        //     $json_data = json_decode($settings->json_data, true);

        //     if (!empty($json_data)) {

        //         if (isset($json_data[0]['valid_till']) && $json_data[0]['valid_till'] != '') {
        //             $validTill = Carbon::createFromFormat('Y-m-d', $json_data[0]['valid_till']);
        //             $currentDate = Carbon::now();

        //             $daysDifference = $currentDate->diffInDays($validTill, false);

        //             if ($daysDifference <= 10 && $daysDifference >= 0) {
        //                 // Within 10 days and not expired yet
        //                 $expiry_msg = "Validity expiring in $daysDifference day(s)";
        //             } elseif ($daysDifference < 0) {
        //                 // Expired
        //                 $expiry_msg = '';
        //             } else {
        //                 // More than 10 days left
        //                 $expiry_msg = '';
        //             }
        //         } else {
        //             $expiry_msg = '';
        //         }



        //         if (isset($json_data[0]['time_zone']) && $json_data[0]['time_zone'] != '') {
        //             $timezone = $json_data[0]['time_zone'];
        //         } else {
        //             $timezone = '';
        //         }

        //         if (isset($json_data[0]['valid_till']) && $json_data[0]['valid_till'] != '') {
        //             $valid_till = $json_data[0]['valid_till'];
        //         } else {
        //             $valid_till = '';
        //         }

        //         if (isset($json_data[0]['site_title']) && $json_data[0]['site_title'] != '') {
        //             $site_title = $json_data[0]['site_title'];
        //         } else {
        //             $site_title = '';
        //         }

        //         if (isset($json_data[0]['primary_color']) && $json_data[0]['primary_color'] != '') {
        //             $primary_color = $json_data[0]['primary_color'];
        //         } else {
        //             $primary_color = '';
        //         }


        //         View::share([
        //             'expiry_msg' => $expiry_msg,
        //             'timezone' => $timezone,
        //             'valid_till' => $valid_till,
        //             'site_title' => $site_title,
        //             'primary_color' => $primary_color,
        //         ]);
        //     }
        // }

        try {
            $settings = Settings::where('status', 1)->first();

            if (isset($settings->json_data)) {
                $json_data = json_decode($settings->json_data, true);

                if (!empty($json_data)) {
                    if (isset($json_data[0]['valid_till']) && $json_data[0]['valid_till'] != '') {
                        $validTill = Carbon::createFromFormat('Y-m-d', $json_data[0]['valid_till']);
                        $currentDate = Carbon::now();

                        $daysDifference = $currentDate->diffInDays($validTill, false);

                        if ($daysDifference <= 10 && $daysDifference >= 0) {
                            // Within 10 days and not expired yet
                            $expiry_msg = "Validity expiring in $daysDifference day(s)";
                        } elseif ($daysDifference < 0) {
                            // Expired
                            $expiry_msg = '';
                        } else {
                            // More than 10 days left
                            $expiry_msg = '';
                        }
                    } else {
                        $expiry_msg = '';
                    }

                    $timezone = $json_data[0]['time_zone'] ?? '';
                    $valid_till = $json_data[0]['valid_till'] ?? '';
                    $site_title = $json_data[0]['site_title'] ?? '';
                    $primary_color = $json_data[0]['primary_color'] ?? '';
                }
            } else {
                $expiry_msg = '';
                $timezone = 'Asia/Kolkata';
                $valid_till = '';
                $site_title = '';
                $primary_color = '';
            }
        } catch (\Exception $e) {

            $expiry_msg = '';
            $timezone = 'Asia/Kolkata';
            $valid_till = '';
            $site_title = '';
            $primary_color = '';
        }

        View::share([
            'expiry_msg' => $expiry_msg,
            'timezone' => $timezone,
            'valid_till' => $valid_till,
            'site_title' => $site_title,
            'primary_color' => $primary_color,
        ]);
    }
}
