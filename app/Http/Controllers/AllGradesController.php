<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllGrades; // Ensure you have this model

class AllGradesController extends Controller
{
    public function index()
    {
        // Fetch unique departments and statuses from the database
        $departments = AllGrades::select('department')->distinct()->pluck('department');
        $statuses = AllGrades::select('status')->distinct()->pluck('status');

        return view("registrar.allgrades", compact('departments', 'statuses'));
    }

    public function getGrades(Request $request)
    {
        $query = AllGrades::query();

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('subject_code', 'LIKE', "%$search%")
                    ->orWhere('descriptive_title', 'LIKE', "%$search%");
            });
        }

        // Apply department filter if selected
        if ($request->has('department') && $request->department !== 'all') {
            $query->where('department', $request->department);
        }



        // Fetch filtered grades
        $grades = $query->get();

        return response()->json($grades);
    }
}
