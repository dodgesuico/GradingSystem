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

        foreach ($users as $user) {
            if ($user->role === 'student') {
                $user->grades = DB::table('grade_logs')
                    ->select('subject_code', 'descriptive_title', 'academic_period', 'prelim', 'midterm', 'semi_finals', 'final', 'remarks', 'created_at')
                    ->where('studentID', $user->studentID)
                    ->get();
            }
        }


        return view('users.user', compact('users', 'departments', 'roles', 'department'));
    }

    public function editUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'department' => 'required|string',
            'roles' => 'required|string', // ✅ Expect a JSON string // Ensure valid roles
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->department = $request->department;
            $user->role = implode(',', json_decode($request->roles, true)); // Store roles as comma-separated

            $user->save();

            return redirect(route("user.show"))->with("success", "User Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user. ' . $e->getMessage());
        }
    }
}
