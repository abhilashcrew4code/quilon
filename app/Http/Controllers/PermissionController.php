<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entry_count         = $request->entry_count ? $request->entry_count : 15;

        $query = Permission::latest()->newQuery();

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('display_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $permissions = $query->paginate($entry_count);

        if ($request->ajax()) { //dd('hi');
            return view('acl.permissions.listPagin', compact('permissions'));
        }
        return view('acl.permissions.list', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission_groups = PermissionGroup::latest()->get();
        return view('acl.permissions.create', compact('permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:permissions|max:255',
            'display_name' => 'required|unique:permissions|max:255',
            'group_id' => 'required',
        ];

        $customMessages = [
            'group_id.required' => 'Please select any permission group.'
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_id' => $request->group_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Permission saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Spatie\Permission\Models\Permission
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $permission_groups = PermissionGroup::latest()->get();
        $permission = Permission::find($id);
        //return response()->json($permission);
        return view('acl.permissions.create', compact('permission', 'permission_groups'));
    }

    /**
     * Update the resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        /*$validatedData = $request->validate([
        	'name' => 'required|unique:roles'. ',id,' . $id,
    	]);*/

        $rules = [
            'name' => 'required|unique:permissions' . ',id,' . $id,
            'display_name' => 'required|unique:permissions' . ',id,' . $id,
            'group_id' => 'required',
        ];

        $customMessages = [
            'group_id.required' => 'Please select any permission group.'
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        Permission::where('id', $id)->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_id' => $request->group_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Permission updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //Permission::find($id)->delete();
        //return response()->json(['success'=>'Permission deleted successfully.']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_permission_group(Request $request)
    {
        return view('acl.permissions.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_permission_group(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:permission_groups|max:255',
        ]);

        PermissionGroup::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success',  'message' => 'Permission Group saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\PermissionGroup
     * @return \Illuminate\Http\Response
     */

    public function edit_permission_group($id)
    {
        $permission_groups = PermissionGroup::find($id);
        //return response()->json($permission);
        return view('acl.permissions.groups.create', compact('permission_groups'));
    }

    /**
     * Update the resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update_permission_group(Request $request, $id)
    {

        $rules = [
            'name' => 'required|unique:permissions' . ',id,' . $id,
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        Permission::where('id', $id)->update([
            'name' => $request->name,
            'group_id' => $request->group_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Permission Group Updated successfully.']);
    }
}
