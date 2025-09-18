<?php

namespace App\Http\Controllers;

use App\Models\FrontOffice;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{

    //Index
    public function index(Request $request)
    {

        $settings = Settings::where('status', 1)->first();
        return view('settings.index', compact('settings'));
    }

    //create
    public function create()
    {
        return view('interactions.create');
    }



    //Store
    public function store(Request $request)
    {


        $rules = [
            'title' => 'required',
        ];

        $customMessages = [
            'title.required' => 'Title is required.',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        // $validatedJson = json_decode($request->json_data);

        $checkSettings = Settings::where('status', 1)->first();
        if (isset($checkSettings)) {


            $record = Settings::findOrFail($checkSettings->id);
            $jsonData = json_decode($record->json_data, true);
            $lastItem = end($jsonData);

            $fieldsToUpdate = [];


            if (isset($lastItem['site_title']) && $lastItem['site_title'] !== $request->site_title) {
                $fieldsToUpdate['site_title'] = $request->site_title;
            } else {

                $fieldsToUpdate['site_title'] = $request->site_title;
            }


            if (isset($lastItem['valid_till']) && $lastItem['valid_till'] !== $request->valid_till) {
                $fieldsToUpdate['valid_till'] = $request->valid_till;
            } else {

                $fieldsToUpdate['valid_till'] = $request->valid_till;
            }



            if (isset($lastItem['time_zone']) && $lastItem['time_zone'] !== $request->time_zone) {
                $fieldsToUpdate['time_zone'] = $request->time_zone;
            } else {

                $fieldsToUpdate['time_zone'] = $request->time_zone;
            }

            if (isset($lastItem['primary_color']) && $lastItem['primary_color'] !== $request->primary_color) {
                $fieldsToUpdate['primary_color'] = $request->primary_color;
            } else {

                $fieldsToUpdate['primary_color'] = $request->primary_color;
            }

            // print_r($fieldsToUpdate);
            // exit;

            if (!empty($fieldsToUpdate)) {
                foreach ($fieldsToUpdate as $key => $value) {
                    $lastItem[$key] = $value;
                }

                array_pop($jsonData);
                $jsonData[] = $lastItem;

                $record->json_data = json_encode($jsonData);

                $record->title = $request->title;
                $record->user_id = Auth::id();
                $record->save();
            }

            return response()->json(['status' => 'success',  'message' => 'Data Updated Successfully']);
        } else {


            $jsonarray[] = [
                "site_title" => $request->site_title,
                "valid_till" => $request->valid_till,
                "time_zone" => $request->time_zone,
                "primary_color" => $request->primary_color,
                // "logo" => $request->file('logo') ? $request->file('logo')->store('your_directory') : null,
            ];

            $json = json_encode($jsonarray);

            $insert_data = new Settings();
            $insert_data->title          = $request->title;
            $insert_data->json_data      = $json;
            $insert_data->user_id     = Auth::id();
            $insert_data->status         = 1;
            $insert_data->save();

            return response()->json(['status' => 'success',  'message' => 'Data Created Successfully']);
        }
    }
}
