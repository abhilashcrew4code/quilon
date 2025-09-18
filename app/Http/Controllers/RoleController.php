<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use App\Models\Permission;
use App\Models\User;
use App\Models\PermissionGroup;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entry_count         = $request->entry_count ? $request->entry_count : 15;
        //$entry_count = 1;
        if (Auth::user()->hasRole('super-admin')) {
            $query = Role::latest()->newQuery();
        } else {
            $query = Role::where('name', '<>', 'super-admin')->latest()->newQuery();
        }

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $roles = $query->paginate($entry_count);


        if ($request->ajax()) { //dd('hi');
            return view('acl.roles.listPagin', compact('roles'));
        }
        return view('acl.roles.list', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('acl.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success',  'message' => 'Role saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Spatie\Permission\Models\Role
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $role = Role::find($id);
        //return response()->json($role);
        return view('acl.roles.create', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Spatie\Permission\Models\Role
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles' . ',id,' . $id,
        ]);

        Role::where('id', $id)->update([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success',  'message' => 'Role Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //Role::find($id)->delete();
        return response()->json(['status' => 'success',  'message' => 'Role deleted successfully.']);
    }

    /**
     * Show the form for assign a new resource.
     * @param \Spatie\Permission\Models\Permission
     * @return \Illuminate\Http\Response
     */
    public function getAssignPermissionOld($id)
    {
        $permissions = Permission::addSelect([
            'group_name' => PermissionGroup::select('name')
                ->whereColumn('id', 'permissions.group_id')
        ])->latest()->get()->groupBy('group_id');
        $group_names = PermissionGroup::pluck('name', 'id');
        $role_id = $id;
        $role = Role::findById($role_id);
        $assigned_permissions = $role->getAllPermissions();
        return view('acl.roles.assignOld', compact('permissions', 'group_names', 'role_id', 'assigned_permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignPermissionOld(Request $request)
    {
        $validatedData = $request->validate([
            'role_id' => 'required',
            'name' => 'required',
        ]);

        $role_id = $request->role_id;
        $role = Role::findById($role_id);
        //$role -> givePermissionTo($request->input('name'));
        $role->syncPermissions($request->input('name'));

        return response()->json(['status' => 'success',  'message' => 'Permission assigned successfully.']);
    }

    public function getAssignRolePermission(Request $request, $roleId)
    {

        $role = Role::find($roleId);
        $assigned_permissions = $role->getAllPermissions();
        $permission_group = PermissionGroup::with('permission')->get();
        return view('acl.roles.assign.roleAssignPermission', [
            'user' => false,
            'role' => $role,
            'permission_group' => $permission_group,
            'assigned_permissions' => $assigned_permissions,
        ]);
    }


    public function postAssignRolePermission(Request $request, $roleId)
    {
        $role = Role::find($roleId);
        $role->syncPermissions($request->input('permissn'));
        return redirect()->route('roles.index')->with('success_message', 'Permission assigned successfully');
    }

    /**
     * Display a listing of the User.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAssignUserPermissionList(Request $request)
    {
        /*if ($request->has('searchArea') && $request->searchArea !='') {

            $data = User::where(function($q1) use ($request) {
                $q1  ->where('username', 'LIKE', '%'.$request->searchArea.'%')
                    ->orWhere('name', 'LIKE', '%'.$request->searchArea.'%')
                    ->orWhere('mobile', 'LIKE', '%'.$request->searchArea.'%')
                    ->orWhere('email', 'LIKE', '%'.$request->searchArea.'%');
            })
            ->paginate(15);
        }
        else {
        }*/
        if (!Auth::user()->hasRole('super-admin')) { //check the logined user hasn't super-admin role
            //$users = User::with('roles')->role(['user','admin'])->where('status','<>','3')->orderBy('id','DESC')->paginate($entry_count);
            $user_arr = array();
            $super_users = User::select('id')->role('super-admin')->get();
            foreach ($super_users as $key => $value) {
                $user_arr[] = $value->id;
            }
            //$users = User::whereNotIn('id',$user_arr)->where('status','<>','3')->orderBy('id','DESC')->paginate($entry_count);  

            $users = User::whereNotIn('id', $user_arr)->where('status', '<>', '3')->orderBy('id', 'DESC')->paginate(15);
        } else {
            $users = User::where('status', '<>', '3')->orderBy('id', 'DESC')->paginate(15);
        }
        if ($request->ajax()) {
            return view('acl.roles.assign.userPermsListPagin', compact('users'));
        }
        return view('acl.roles.assign.userPermsList', compact('users'));
    }

    public function getAssignUserPermission(Request $request, $userId)
    {

        $user = User::find($userId);
        $assigned_permissions = $user->getDirectPermissions();
        $permission_group = PermissionGroup::with('permission')->get();
        return view('acl.roles.assign.roleAssignPermission', [
            'user' => $user,
            'role' => false,
            'permission_group' => $permission_group,
            'assigned_permissions' => $assigned_permissions,
        ]);
    }


    public function postAssignUserPermission(Request $request, $userId)
    {
        $user = User::find($userId);
        $user->syncPermissions($request->input('permissn'));
        return redirect()->route('acl.user.permissions.view')->with('success_message', 'Permission Updated Successfully ');
    }
}
