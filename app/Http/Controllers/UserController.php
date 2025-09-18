<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChangePasswordLog;
use App\Models\AcccessManageLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entry_count         = $request->entry_count ? $request->entry_count : 10;

        if (!Auth::user()->hasRole('super-admin')) { //check the logined user hasn't super-admin role
            //$users = User::with('roles')->role(['user','admin'])->where('status','<>','3')->orderBy('id','DESC')->paginate($entry_count);
            $user_arr = array();
            $super_users = User::select('id')->role('super-admin')->get();
            foreach ($super_users as $key => $value) {
                $user_arr[] = $value->id;
            }

            $query = User::whereNotIn('id', $user_arr)->where('status', '<>', '3')->newQuery();
        } else {
            $query = User::with('roles')->where('status', '<>', '3')->newQuery();
        }

        if ($request->search != '') {
            $query->where(function ($q1) use ($request) {
                $q1->where('username', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->search . '%');
            });
        }


        $users = $query->orderBy('id', 'DESC')->paginate($entry_count);


        if ($request->ajax()) { //dd('hi');
            return view('users.listPagin', compact('users'));
        }
        return view('users.list', compact('users'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check whether the logined user has 'super-admin' role. if no, select role names except super-admin
        if (Auth::user()->hasRole('super-admin')) {
            $roles = Role::all()->pluck('name');
        } else {
            $roles = Role::where('name', '<>', 'super-admin')->pluck('name');
        }
        //dd($roles);
        return view('users.create', compact('roles'));
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
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'username' => 'required|alpha_dash|unique:users|max:255',
            'password' => 'required|min:8',
            'role_name' => 'required',
        ];

        $customMessages = [
            'username.required' => 'User Name is required.',
            'username.alpha_dash' => 'The username may only contain letters, numbers, dashes and underscores.',
            'role_name.required' => 'Please select any role.',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'user_id' => Auth::id(),
            'status' => 1,
        ]);


        $user->assignRole($request->input('role_name'));
        return response()->json(['status' => 'success',  'message' => 'New User Created Successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Spatie\Permission\Models\user
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //$user = User::find($id);
        $user = User::find($id);
        $assigned_roles = $user->getRoleNames();
        if (Auth::user()->hasRole('super-admin')) {
            $roles = Role::all()->pluck('name');
        } else {
            $roles = Role::where('name', '<>', 'super-admin')->pluck('name');
        }
        return view('users.create', compact('roles', 'user', 'assigned_roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Spatie\Permission\Models\user
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        $rules = [
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'username' => 'required|unique:users' . ',id,' . $id . '|max:255',
            'role_name' => 'required',
        ];

        $customMessages = [
            'username.required' => 'User Name is required.',
            'role_name.required' => 'Please select any role.',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $oldData = User::find($id);

        $update_stat = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'user_id' => Auth::id(),
        ]);


        if ($update_stat == 1) {

            $user = User::find($id);
            $user->syncRoles([$request->input('role_name')]);
        }

        return response()->json(['status' => 'success',  'message' => 'User updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $update_stat = User::where('id', $id)->update([
            'status' => 3,
            //'user_id'=>Auth::id(),
        ]);
        //user::find($id)->delete();
        return response()->json(['status' => 'success',  'message' => 'user deleted successfully.']);
    }

    public function getChangePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user)
            return response()->json(['status' => 'error',  'message' => 'Invalid User.']);
        return view('users.password', compact('user'));
    }

    public function changePassword(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::find($id);
        if (!$user)
            return response()->json(['status' => 'error',  'message' => 'No User Found.']);

        $user->password = Hash::make($request->password);
        $user->last_password_change = Carbon::now()->toDateString();
        $user->save();


        return response()->json(['status' => 'success',  'message' => 'Password Changed Successfully.']);
    }

    //Manage Portal Access
    public function managePortalAccess(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user)
            return response()->json(['status' => 'error',  'message' => 'No User Found.']);
        $status = ($user->status == 1) ? 2 : 1;

        $user->status           = $status;
        $user->save();

        if ($status == 1) {
            return response()->json(['status' => 'success',  'message' => 'User Unblocked Successfully.']);
        } else {
            return response()->json(['status' => 'success',  'message' => 'User Blocked Successfully.']);
        }
    }

    //Password Change
    public function passwordChange()
    {
        $user = Auth::user();
        return view('users.change-password', compact('user'));
    }

    //Password Update
    public function passwordUpdate(Request $request)
    {

        $validatedData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = Auth::user();


        $user->password = Hash::make($request->password);
        $user->last_password_change = Carbon::now()->toDateString();
        $user->save();

        return response()->json(['status' => 'success',  'message' => 'Password Changed Successfully.']);
    }

    //User Profile
    public function userProfile()
    {

        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    //Update Profile
    public function updateProfile(Request $request)
    {

        $user = Auth::user();


        $validatedData = $request->validate([
            'name' => ['required', 'string', 'unique:users,name, ' . $user->id],
            'username' => ['required', 'string', 'unique:users,username, ' . $user->id],
        ]);

        // exit;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();

        return response()->json(['status' => 'success',  'message' => 'Profile Updated Successfully.']);
    }

    //Start Impersonate
    public function startImpersonate($id)
    {
        $impersonate_user = User::find($id);
        Auth::user()->impersonate($impersonate_user);
        // echo $impersonate_user->name." --- ";
        // echo Auth::user()->name; exit;
        return redirect()->route('home')->with('success_message', 'Successfully impersonated to ' . $impersonate_user->name . ' ');
        // return response()->json(['status' => 'success', 'data'=>$route]);
    }

    //Stop Impersonate
    public function stopImpersonate()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('home')->with('success_message', 'Welcome back ' . Auth::user()->name . ' ');
        // return response()->json(['status' => 'success', 'data'=>$route]);

    }
}
