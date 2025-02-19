<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $query = User::query();

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Apply department filter
        if ($request->has('department') && !empty($request->department)) {
            $query->where('department', $request->department);
        }

        // Apply role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        if ($request->ajax()) {
            return response()->json(['users' => $users]);
        }

        // Get unique departments and roles for filters
        $departments = User::select('department')->distinct()->pluck('department');
        $department = DB::table('departments')->pluck('department_name'); // Assuming your department table has a 'name' column
        $roles = User::select('role')->distinct()->pluck('role');

        return view('users.user', compact('users', 'departments', 'roles', 'department'));
    }

    public function editUser(Request $request)
    {
        // Validate request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'department' => 'nullable|string',
            'roles' => 'nullable|array', // Ensure roles are an array
        ]);

        // Find the user
        $user = User::findOrFail($request->user_id);

        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->department;

        // Convert roles array to a comma-separated string before saving
        $user->role = $request->has('roles') ? implode(',', $request->roles) : null;

        // Save user changes
        $user->save();

        return response()->json(['message' => 'User updated successfully!']);
    }
}
