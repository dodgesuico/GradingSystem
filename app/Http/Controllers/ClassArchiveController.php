<?php
namespace App\Http\Controllers;

use App\Models\ClassArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth

class ClassArchiveController extends Controller
{
    public function index(Request $request)
    {
        $termOrder = ['Prelim', 'Midterm', 'Semi-Finals', 'Finals'];

        // Get logged-in user
        $loggedInUser = Auth::user();
        $loggedInInstructor = $loggedInUser->name;
        $roles = explode(',', $loggedInUser->role); // Convert roles to an array

        // Check if the user is an admin
        $isAdmin = in_array('admin', $roles);

        // Apply filters
        $query = ClassArchive::query();

        // If the user is not an admin, filter by instructor
        if (!$isAdmin) {
            $query->where('instructor', $loggedInInstructor);
        }

        // Apply additional filters based on the request
        if ($request->has('academic_year') && $request->academic_year != '') {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->has('subject_code') && $request->subject_code != '') {
            $query->where('subject_code', 'LIKE', '%' . $request->subject_code . '%');
        }

        $records = $query->orderBy('academic_year', 'desc')
            ->orderBy('academic_period')
            ->orderBy('descriptive_title')
            ->orderBy('subject_code')
            ->get();

        // Get unique instructors for the dropdown
        $uniqueInstructors = ClassArchive::selectRaw('DISTINCT TRIM(LOWER(instructor)) as instructor')
        ->orderBy('instructor')
        ->pluck('instructor')
        ->map(fn($name) => ucwords($name)) // Capitalize first letter of each word
        ->unique()
        ->values();




        // Group the data
        $archivedData = $records->groupBy('academic_year')
            ->map(function ($yearGroup) use ($termOrder) {
                return $yearGroup->groupBy('academic_period')
                    ->map(function ($periodGroup) use ($termOrder) {
                        return $periodGroup->groupBy('instructor')
                            ->map(function ($instructorGroup) use ($termOrder) {
                                return $instructorGroup->groupBy('descriptive_title')
                                    ->map(function ($titleGroup) use ($termOrder) {
                                        return $titleGroup->groupBy('subject_code')
                                            ->map(function ($subjectGroup) use ($termOrder) {
                                                return $subjectGroup->groupBy('periodic_term')
                                                    ->sortBy(function ($_, $key) use ($termOrder) {
                                                        return array_search($key, $termOrder);
                                                    });
                                            });
                                    });
                            });
                    });
            });

        return view('instructor.my_class_archive', compact('archivedData', 'uniqueInstructors'));
    }
}
