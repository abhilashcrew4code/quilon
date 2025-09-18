<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    //List
    public function index(Request $request)
    {
        $entry_count         = $request->entry_count ? $request->entry_count : 10;

        $query = Announcement::where('status', '<>', '3')->newQuery();

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('announcement', 'LIKE', '%' . $request->search . '%');
            });
        }

        $announcements = $query->orderBy('id', 'DESC')->paginate($entry_count);

        if ($request->ajax()) {
            return view('announcements.listPagin', compact('announcements'));
        }
        return view('announcements.list', compact('announcements'));
    }

    //Create
    public function create()
    {
        return view('announcements.create');
    }

    //Store
    public function store(Request $request)
    {
        $rules = [
            'announcement' => 'required',
        ];

        $customMessages = [
            'announcement.required' => 'Announcement is required.',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $announcement = Announcement::create([
            'announcement' => $request->announcement,
            'user_id' => Auth::id(),
            'status' => 1,
        ]);

        return response()->json(['status' => 'success',  'message' => 'New Announcement Added Successfully']);
    }

    //Show
    public function show(string $id)
    {
        //
    }

    // Edit
    public function edit(string $id)
    {
        $announcement = Announcement::find($id);
        return view('announcements.create', compact('announcement'));
    }

    //Update
    public function update(Request $request, string $id)
    {
        $rules = [
            'announcement' => 'required',
        ];

        $customMessages = [
            'announcement.required' => 'Announcement is required.',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $update_stat = Announcement::where('id', $id)->update([
            'announcement' => $request->announcement,
            'user_id' => Auth::id(),
        ]);


        return response()->json(['status' => 'success',  'message' => 'Announcement updated Successfully']);
    }

    //Delete
    public function deleteData(string $id)
    {
        $announcement = Announcement::find($id);
        if (!$announcement)
            return response()->json(['status' => 'error',  'message' => 'No Announcement Found.']);
        $status = ($announcement->status == 1) ? 2 : 1;

        $announcement->status           = $status;
        $announcement->save();

        if ($status == 1) {
            return response()->json(['status' => 'success',  'message' => 'Announcement Unblocked Successfully.']);
        } else {
            return response()->json(['status' => 'success',  'message' => 'Announcement Blocked Successfully.']);
        }
    }
}
