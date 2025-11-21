<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        return view('user.index');
    }

    public function userData()
    {
        $users = User::whereIn('user_type', [EVENT_ORGANIZER])->orderBy('created_at', 'desc');

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('role', function ($user) {
                return $user->getRoleNames()->first();
            })
            ->addColumn('suspend', function ($user) {
                return view('user.partials.suspend_switch', compact('user'))->render();
            })
            ->editColumn('dob', function ($user) {
                return $user->dob ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                return view('user.partials.actions', compact('row'))->render();
            })

            ->orderByNullsLast()
            ->rawColumns(['suspend', 'action'])
            ->make(true);
    }

    public function organizers()
    {
        //$organizers = User::where('user_type', EVENT_ORGANIZER)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.organizers');
    }

    public function create()
    {
        $roles = Role::where('user_type', '<>', 1)->pluck('name', 'id'); // id => name
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|exists:roles,id',
        ]);

        $token = Str::random(64); // generate random token

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 0,
            'remember_token' => $token,
        ]);

        $role = Role::find($request->role);
        if ($role) {
            $user->assignRole($role->name);
        }

        $user->user_type = $role->user_type;
        $user->save();


        // Queue Welcome Mail with Verification Link
        Mail::to($user->email)->queue(new WelcomeMail($user, $request->password));


        return redirect()->route(routePrefix() . 'users.index')->with('success', 'User created successfully');
    }



    public function edit(User $user)
    {
        $roles = Role::where('user_type', '<>', 1)->pluck('name', 'id');
        // $roles = Role::pluck('name', 'id'); // example if you have roles
        return view('user.edit', compact('user', 'roles'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id', // validate role
        ]);

        // Update basic fields
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Update role and user_type
        $role = Role::find($request->role);
        if ($role) {
            $user->syncRoles($role->name); // remove old roles and assign new
            $user->user_type = $role->user_type; // update user_type field
        }

        $user->save();

        return redirect()->route(routePrefix() . 'users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete(); // or soft delete if needed
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function verifyEmail($token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route(routePrefix() . 'login')->with('error', 'Invalid or expired verification link.');
        }

        // Mark as verified (you can add a verified column or use email_verified_at if available)
        $user->email_verified_at = now();
        $user->is_verified = true;
        $user->remember_token = null; // clear token after verification
        $user->save();

        return redirect()->route(routePrefix() . 'login')->with('success', 'Email verified successfully! You can now login.');
    }



    public function getData(Request $request)
    {
        $users = User::where('user_type', EVENT_ORGANIZER);

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('verified', function ($user) {
                return view('user.partials.verified_switch', compact('user'))->render();
            })
            ->addColumn('suspend', function ($user) {
                return view('user.partials.suspend_switch', compact('user'))->render();
            })
            ->editColumn('dob', function ($user) {
                return $user->dob ?? 'N/A';
            })
            ->addColumn('created_at', fn($user) => $user->created_at)
            ->rawColumns(['verified', 'suspend'])
            ->make(true);
    }




    public function buyers()
    {

        return view('user.buyers');
    }

    public function buyersGetData(Request $request)
    {
        $users = User::where('user_type', BUYER);

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('phone', function ($user) {
                return $user->phone ?? 'N/A';
            })
            ->make(true);
    }




    public function suspend(User $user)
    {

        $user->update(['status' => 'suspended']);

        return response()->json([
            'status' => true,
            'message' => 'User suspended successfully',
        ]);
    }

    public function unsuspend(User $user)
    {
        $user->update(['status' => 'active']);

        return response()->json([
            'status' => true,
            'message' => 'User unsuspended successfully',
        ]);
    }

    public function verify(User $user)
    {

        // if (!$user->hasRole('event-organizer')) {
        //     return response()->json(['error' => 'Only organizers can be verified'], 422);
        // }

        if (!$user->is_verified) {
            $user->update(['is_verified' => true]);
            $msg = 'Organizer verified successfully';
        } else {
            $user->update(['is_verified' => false]);
            $msg = 'Organizer un-verified successfully';
        }

        return response()->json([
            'status' => true,
            'message' => $msg,
        ]);
    }
}
