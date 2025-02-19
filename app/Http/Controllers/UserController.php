<?php

namespace App\Http\Controllers;

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
        $roles = User::select('role')->distinct()->pluck('role');

        return view('users.user', compact('users', 'departments', 'roles'));
    }
}
