@extends('layouts.default')

<title>@yield('title', 'CKCM Grading | View Class Details')</title>

@section('content')

    {{-- start of dashboard --}}
    <div class="dashboard">
        <div class="header-container">

            <h1>Class Details</h1>
            <h2>{{ $class->descriptive_title }}</h2>


            <div class="sub-header-container">
                <p><strong>Subject Code:</strong> {{ $class->subject_code }}</p>
                <p><strong>Instructor:</strong> {{ $class->instructor }}</p>
                <p><strong>Academic Period:</strong> {{ $class->academic_period }}</p>
                <p><strong>Schedule:</strong> {{ $class->schedule }}</p>
                <p><strong>Status:</strong> <span class="status {{ strtolower($class->status) }}">{{ $class->status }}</span>
                </p>
            </div>

            <style>
                .status.active {
                    color: green;
                    font-weight: bold;
                }

                .status.ended {
                    color: gray;
                    font-weight: bold;
                }

                .status.dropped {
                    color: red;
                    font-weight: bold;
                }
            </style>

        </div>


        <style>
            .header-container {
                display: flex;
                flex-direction: column;
            }

            .sub-header-container {
                display: flex;
                justify-content: space-between;
                color: var(--color6);
                padding: 0 10px 0 0;
            }

            .search-container {
                width: 100%;
                display: flex;
                justify-content: space-between;
                margin: 10px 0;
            }
        </style>

        {{-- <div class="search-container">
            <input type="text" class="search" name="" id="" placeholder="🔎 Quick Search...">
        </div> --}}


        <script>
            function openAddStudentModal() {
                document.getElementById('addStudentModal').style.display = 'block';
            }

            function closeAddStudentModal() {
                document.getElementById('addStudentModal').style.display = 'none';
            }

            function fillStudentInfo() {
                const selectedOption = document.getElementById('student').selectedOptions[0];

                if (selectedOption) {
                    document.getElementById('student_id').value = selectedOption.getAttribute('data-id');
                    document.getElementById('name').value = selectedOption.getAttribute('data-name');
                    document.getElementById('email').value = selectedOption.getAttribute('data-email');
                    document.getElementById('department').value = selectedOption.getAttribute('data-department');
                }
            }

            // Close the modal if the user clicks outside of it
            window.onclick = function(event) {
                const modal = document.getElementById('addStudentModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        </script>


        <div class="message-container">
            @if (session('error'))
                <div class="alert alert-danger">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong> Please check the following issues:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif

            @if (session('warnings'))
                <div class="alert alert-warning">
                    @foreach (session('warnings') as $warning)
                        <p>{{ $warning }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        @if (
            !(
                $finalGrades->where('classID', $class->id)->isNotEmpty() &&
                $finalGrades->where('classID', $class->id)->first()->status
            ))

            <div id="grade-content">


                <div style="display:flex; flex-direction:row; justify-content:space-between">
                    <h2 style="margin:10px 0;">Class Students List</h2>
                    <button class="add-btn" onclick="openAddStudentModal()"><i class="fa-solid fa-plus"></i> Add
                        Student</button>
                </div>

                <div class="container">
                    @if ($classes_student->isEmpty())
                        <p style="color:gray">No students have been added to this class yet.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <!-- <th>ID</th> -->
                                    <th>Class ID</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes_student as $classes_students)
                                    <tr>
                                        <!-- <td>{{ $classes_students->id }}</td> -->
                                        <td>{{ $classes_students->classID }}</td>
                                        <td>{{ $classes_students->studentID }}</td>
                                        <td>{{ $classes_students->name }}</td>
                                        <td>{{ $classes_students->email }}</td>
                                        <td>{{ $classes_students->department }}</td>
                                        <td style="text-align:center;">

                                            <button class="delete-btn"
                                                onclick="openDeleteClassModal({{ $classes_students->id }})"><i
                                                    class="fa-solid fa-trash"></i>Remove</button>
                                        </td>
                                    </tr>


                                    <!-- Delete Modal for each student -->
                                    <div id="deleteClassModal{{ $classes_students->id }}" class="modal">
                                        <div class="modal-content">
                                            <h2 style="color:var(--color1); text-align:center;margin-bottom:1.5rem;">Remove
                                                Student
                                            </h2>
                                            <p style="color:var(--color-red);font-size:1.5rem; text-align:center;">
                                                Are you sure you want to remove this student?
                                            </p>
                                            <p style="color:var(--color5);font-size:1.2rem; text-align:center;">
                                                Student Name: {{ $classes_students->name }}
                                            </p>

                                            <!-- Checkbox to confirm deletion -->
                                            <div
                                                style="display:flex;justify-content:center; text-align:center; margin: 10px 0; align-items:center; gap:10px;">
                                                <input type="checkbox" id="confirmDelete{{ $classes_students->id }}"
                                                    onchange="toggleDeleteButton({{ $classes_students->id }})">
                                                <label style="color:var(--color6);"
                                                    for="confirmDelete{{ $classes_students->id }}">I am sure to delete
                                                    this</label>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn-cancel"
                                                    onclick="closeDeleteClassModal({{ $classes_students->id }})">Cancel</button>

                                                <form method="POST"
                                                    action="{{ route('class.removestudent', ['class' => $class->id, 'student' => $classes_students->studentID]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-btn disabled-btn"
                                                        id="deleteBtn{{ $classes_students->id }}" disabled>
                                                        <i class="fa-solid fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>


                {{-- start of grading and score --}}

                <h2 class="grading-title" style="margin: 20px 0">Grading & Scores</h2>

                @foreach (['Prelim', 'Midterm', 'Semi-Finals', 'Finals'] as $term)
                    <div class="grading-score-section">
                        <button type="button" class="grading-score-toggle"
                            onclick="toggleSection('grading-score-{{ $term }}', this)">
                            {{ $term }} <i class="fa-solid fa-folder"></i>
                        </button>
                        <div id="grading-score-{{ $term }}" class="grading-score-content">
                            <h3>Grading</h3>
                            <form action="{{ route('class.addPercentageAndScores', ['class' => $class->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="periodic_terms[]" value="{{ $term }}">
                                <div class="calculation-base-container">
                                    @foreach (['quiz' => 'Quizzes', 'attendance' => 'Attendance/Behavior', 'assignment' => 'Assignments/Participation/Project', 'exam' => 'Exam'] as $key => $category)
                                        <div class="calculation-container">
                                            @php
                                                $percentageValue =
                                                    $percentage && $percentage->where('periodic_term', $term)->first()
                                                        ? $percentage->where('periodic_term', $term)->first()
                                                            ->{$key . '_percentage'}
                                                        : '';
                                            @endphp
                                            <h4>{{ $category }}</h4>
                                            <div class="calculation-content">
                                                <label>Percentage (%)</label>
                                                <input type="number"
                                                    name="{{ $key }}_percentage[{{ $term }}]"
                                                    value="{{ old($key . '_percentage.' . $term, $percentageValue) }}"
                                                    min="0" max="100" required>
                                            </div>
                                            <div class="calculation-content">
                                                <label>Total Score</label>
                                                <input type="number"
                                                    name="{{ $key }}_total_score[{{ $term }}]"
                                                    value="{{ old($key . '_total_score.' . $term, optional(optional($percentage)->where('periodic_term', $term)->first())->{$key . '_total_score'} ?? '') }}"
                                                    min="0">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="save-btn" style="margin: 5px 0 0 0"><i
                                        class="fa-solid fa-floppy-disk"></i> Save Grading and Total Score</button>
                                        <em style="color: var(--color5); margin-left: 10px;">Note: Do not forget to save first before entering the student scores.</em>
                            </form>
                            <h3 style="margin:5px 0 10px 0">{{ $term }} Scores (Raw)</h3>
                            <form action="{{ route('class.addquizandscore', ['class' => $class->id]) }}" method="post">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="periodic_term" value="{{ $term }}">
                                <table class="score-table">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            @foreach (['Quizzes', 'Attendance/Behavior', 'Assignments/Participation/Project', 'Exam'] as $category)
                                                <th>{{ $category }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            @for ($i = 0; $i < 4; $i++)
                                                <td>
                                                    <div class="content-container">
                                                        @foreach (['Accumulated Score', 'Transmuted Grade', 'Grade'] as $label)
                                                            <div class="cell-content">{{ $label }}</div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endfor
                                        </tr>
                                        @foreach ($quizzesandscores->where('periodic_term', $term) as $quizzesandscore)
                                            @php
                                                $student = $classes_student->firstWhere(
                                                    'studentID',
                                                    $quizzesandscore->studentID,
                                                );
                                                $score = $quizzesandscores
                                                    ->where('studentID', optional($student)->studentID) // Prevent error if $student is null
                                                    ->where('periodic_term', $term)
                                                    ->first();
                                                $computedGrade = null;
                                            @endphp
                                            <tr>
                                                <td style="padding: 5px;">{{ $student ? $student->name : 'N/A' }}</td>
                                                @foreach (['quizzez', 'attendance_behavior', 'assignments', 'exam'] as $field)
                                                    @php
                                                        $fieldScore = $score ? $score->$field : null;
                                                        $transmutedGrade = null;

                                                        // Get percentage data for this class and period
                                                        $percentageData = DB::table('percentage')
                                                            ->where('classID', $class->id)
                                                            ->where('periodic_term', $term)
                                                            ->first();

                                                        // Define field percentage and total score field
                                                        $fieldPercentage = 0;
                                                        $totalScoreField = null;

                                                        if ($percentageData) {
                                                            match ($field) {
                                                                'quizzez' => [
                                                                    ($fieldPercentage =
                                                                        $percentageData->quiz_percentage ?? 0),
                                                                    ($totalScoreField = 'quiz_total_score'),
                                                                ],
                                                                'attendance_behavior' => [
                                                                    ($fieldPercentage =
                                                                        $percentageData->attendance_percentage ?? 0),
                                                                    ($totalScoreField = 'attendance_total_score'),
                                                                ],
                                                                'assignments' => [
                                                                    ($fieldPercentage =
                                                                        $percentageData->assignment_percentage ?? 0),
                                                                    ($totalScoreField = 'assignment_total_score'),
                                                                ],
                                                                'exam' => [
                                                                    ($fieldPercentage =
                                                                        $percentageData->exam_percentage ?? 0),
                                                                    ($totalScoreField = 'exam_total_score'),
                                                                ],
                                                                default => null,
                                                            };
                                                        }

                                                        if (!empty($fieldScore) && $totalScoreField) {
                                                            $totalScore = $percentageData->$totalScoreField ?? 0;

                                                            // Try to find an exact match
                                                            $transmutedGradeEntry = DB::table('transmuted_grade')
                                                                ->where('score_bracket', $totalScore)
                                                                ->where('score', $fieldScore)
                                                                ->first();

                                                            if (!$transmutedGradeEntry) {
                                                                // If no exact match, get the closest lower score
                                                                $nearestLower = DB::table('transmuted_grade')
                                                                    ->where('score_bracket', $totalScore)
                                                                    ->where('score', '<=', $fieldScore)
                                                                    ->orderBy('score', 'desc') // Get the highest lower value
                                                                    ->first();

                                                                $transmutedGrade = $nearestLower
                                                                    ? $nearestLower->transmuted_grade
                                                                    : null;
                                                            } else {
                                                                $transmutedGrade =
                                                                    $transmutedGradeEntry->transmuted_grade;
                                                            }

                                                            if (!is_null($transmutedGrade)) {
                                                                $computedGrade =
                                                                    ($transmutedGrade * $fieldPercentage) / 100;
                                                            }
                                                        }
                                                    @endphp


                                                    <td class="cell-content-container">
                                                        <div class="content-container">
                                                            <div class="cell-content">
                                                                <input type="number"
                                                                    name="scores[{{ $student ? $student->studentID : '' }}][{{ $field }}]"
                                                                    value="{{ $score && $score->$field !== null ? number_format($score->$field, 2) : '0.00' }}"
                                                                    min="0" step="0.01">
                                                            </div>
                                                            <div class="cell-content">
                                                                <p>{{ $transmutedGrade ?? '' }}</p>
                                                            </div>
                                                            <div class="cell-content">
                                                                <p>{{ $computedGrade !== null ? number_format($computedGrade, 2) : '' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="submit" class="save-btn" style="margin: 10px 0"><i
                                        class="fa-solid fa-arrows-rotate"></i> Calculate and Update</button>
                                        <em style="color: var(--color5); margin-left: 10px;">Note: Do not exceed with the total score.</em>
                            </form>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let isLocked = @json($finalGrades->isNotEmpty() && $finalGrades->first()->status);
                if (isLocked) {
                    document.getElementById("grade-content").style.display = "none";
                }
            });
        </script>

        <style>
            .grading-score-section {
                margin-bottom: 15px;
                border-radius: 5px;
                overflow: hidden;
                border: 1px solid var(--color7);
            }

            .grading-score-toggle {
                width: 100%;
                text-align: center;
                padding: 10px;
                font-size: 1.2rem;
                font-weight: bold;
                border: none;
                cursor: pointer;
                transition: background 0.3s;
                background: var(--color9);

            }

            .grading-score-content {
                display: none;
                padding: 15px;
                transition: all 0.3s ease-in-out;
                background: var(--color9b);
            }

            .score-table td {
                padding: 0;
                border: 1px solid var(--color7)
            }
        </style>

        <script>
            function toggleSection(id, btn) {
                let section = document.getElementById(id);
                let icon = btn.querySelector("i");

                if (section.style.display === "none" || section.style.display === "") {
                    section.style.display = "block";
                    icon.classList.remove("fa-folder");
                    icon.classList.add("fa-folder-open");
                } else {
                    section.style.display = "none";
                    icon.classList.remove("fa-folder-open");
                    icon.classList.add("fa-folder");
                }
            }
        </script>

        {{-- end of grading and score --}}











        @php
            // Store total grades per student and per period
            $studentGrades = [];

            foreach ($classes_student as $student) {
                $studentID = $student->studentID;
                $studentGrades[$studentID] = [
                    'Prelim' => 0,
                    'Midterm' => 0,
                    'Midterm Raw' => 0,
                    'Semi-Finals' => 0,
                    'Semi-Finals Raw' => 0,
                    'Finals' => 0,
                    'Finals Raw' => 0,
                    'Remarks' => '',
                ];

                foreach (['Prelim', 'Midterm', 'Semi-Finals', 'Finals'] as $period) {
                    $totalPeriodGrade = 0;

                    // Get percentage data for this period
                    $percentageData = DB::table('percentage')
                        ->where('classID', $class->id)
                        ->where('periodic_term', $period)
                        ->first();

                    foreach (['quizzez', 'attendance_behavior', 'assignments', 'exam'] as $field) {
                        $score = $quizzesandscores
                            ->where('studentID', $studentID)
                            ->where('periodic_term', $period)
                            ->first();
                        $fieldScore = $score ? $score->$field : null;

                        // Ensure $totalScoreField is properly assigned
                        $fieldPercentage = 0;
                        $totalScoreField = null;

                        if ($percentageData) {
                            switch ($field) {
                                case 'quizzez':
                                    $fieldPercentage = $percentageData->quiz_percentage ?? 0;
                                    $totalScoreField = 'quiz_total_score';
                                    break;
                                case 'attendance_behavior':
                                    $fieldPercentage = $percentageData->attendance_percentage ?? 0;
                                    $totalScoreField = 'attendance_total_score';
                                    break;
                                case 'assignments':
                                    $fieldPercentage = $percentageData->assignment_percentage ?? 0;
                                    $totalScoreField = 'assignment_total_score';
                                    break;
                                case 'exam':
                                    $fieldPercentage = $percentageData->exam_percentage ?? 0;
                                    $totalScoreField = 'exam_total_score';
                                    break;
                            }
                        }

                        if ($fieldScore !== null && $totalScoreField) {
                            // Get total possible score for this field
                            $totalScore = $percentageData->$totalScoreField ?? 0;

                            // Find the correct transmuted grade
                            $transmutedGradeEntry = DB::table('transmuted_grade')
                                ->where('score_bracket', $totalScore)
                                ->where('score', '<=', $fieldScore) // Find the closest lower score
                                ->orderBy('score', 'desc')
                                ->first();

                            $transmutedGrade = $transmutedGradeEntry ? $transmutedGradeEntry->transmuted_grade : null;

                            if (!is_null($transmutedGrade)) {
                                // Compute final grade based on field percentage
                                $computedGrade = max(0, ($transmutedGrade * $fieldPercentage) / 100);
                                $totalPeriodGrade += $computedGrade;
                            }
                        }
                    }

                    // Store raw grade
                    if ($period !== 'Prelim') {
                        $studentGrades[$studentID]["{$period} Raw"] = $totalPeriodGrade;
                    }

                    // Store total grade for this period
                    $studentGrades[$studentID][$period] = $totalPeriodGrade;
                }

                // Compute final grades per term using weighted formula
                // Compute final grades per term using weighted formula
                $studentGrades[$studentID]['Midterm'] =
                    0.33 * $studentGrades[$studentID]['Prelim'] + 0.67 * $studentGrades[$studentID]['Midterm Raw'];

                $studentGrades[$studentID]['Semi-Finals'] =
                    0.33 * $studentGrades[$studentID]['Midterm'] + 0.67 * $studentGrades[$studentID]['Semi-Finals Raw'];

                // Fetch the final transmutation table
                $transmutations = DB::table('final_transmutation')
                    ->orderBy('grades', 'asc') // Ensure it's ordered properly
        ->get();

    // Function to find the transmutation based on final grade
    if (!function_exists('getTransmutation')) {
        function getTransmutation($finalGrade, $transmutations)
        {
            foreach ($transmutations as $transmutation) {
                if ($finalGrade >= $transmutation->grades) {
                    $matchedTransmutation = $transmutation;
                } else {
                    break;
                }
            }
            return $matchedTransmutation ?? null;
        }
    }

    // Compute Finals (same as before)
    $studentGrades[$studentID]['Finals'] =
        0.33 * $studentGrades[$studentID]['Semi-Finals'] + 0.67 * $studentGrades[$studentID]['Finals Raw'];

    // Get the corresponding transmutation
    $transmutation = getTransmutation($studentGrades[$studentID]['Finals'], $transmutations);

    if ($transmutation) {
        // Set transmuted grade and remarks
        $studentGrades[$studentID]['Finals'] = $transmutation->transmutation;
        $studentGrades[$studentID]['Remarks'] = $transmutation->remarks;
    } else {
        // Default to FAILED if no match is found
        $studentGrades[$studentID]['Remarks'] = 'FAILED';
                }
            }
        @endphp













        <h2 style="margin: 10px 0">Grades</h2>

        <div style="margin-bottom: 10px;">
            <button onclick="toggleRawColumns()" class="toggle-btn">
                <i class="fa-solid fa-eye"></i> Show Raw Columns
            </button>
        </div>

        <h3
            style="color: {{ $finalGrades->isNotEmpty() && $finalGrades->first()->status ? 'var(--color-green)' : 'gray' }}; margin-bottom:10px;">
            Status:
            <strong>
                {{ $finalGrades->isNotEmpty() && $finalGrades->first()->status ? 'Locked' : 'Not Locked Yet' }}
            </strong>
        </h3>

        <div class="grade-sheet-container">
            <form action="{{ route('finalgrade.lock') }}" method="POST">
                @csrf
                @method('POST')
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Prelim</th>
                            <th class="raw-column" style="display: none;">Midterm (Raw)</th> <!-- Display only -->
                            <th>Midterm</th>
                            <th class="raw-column" style="display: none;">Semi-Finals (Raw)</th> <!-- Display only -->
                            <th>Semi-Finals</th>
                            <th class="raw-column" style="display: none;">Finals (Raw)</th> <!-- Display only -->
                            <th>Final</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes_student as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ number_format($studentGrades[$student->studentID]['Prelim'], 2) }}</td>
                                <td class="raw-column" style="display: none;">
                                    {{ number_format($studentGrades[$student->studentID]['Midterm Raw'], 2) }}</td>
                                <!-- Display only -->
                                <td>{{ number_format($studentGrades[$student->studentID]['Midterm'], 2) }}</td>
                                <td class="raw-column" style="display: none;">
                                    {{ number_format($studentGrades[$student->studentID]['Semi-Finals Raw'], 2) }}</td>
                                <!-- Display only -->
                                <td>{{ number_format($studentGrades[$student->studentID]['Semi-Finals'], 2) }}</td>
                                <td class="raw-column" style="display: none;">
                                    {{ number_format($studentGrades[$student->studentID]['Finals Raw'], 2) }}</td>
                                <!-- Display only -->
                                <td>{{ number_format($studentGrades[$student->studentID]['Finals'], 2) }}</td>
                                <td><strong>{{ $studentGrades[$student->studentID]['Remarks'] }}</strong></td>

                                <!-- Hidden inputs (ONLY SAVING PRELIM, MIDTERM, SEMI-FINALS, FINAL, and REMARKS) -->
                                <input type="hidden" name="grades[{{ $student->studentID }}][classID]"
                                    value="{{ $student->classID }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][studentID]"
                                    value="{{ $student->studentID }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][name]"
                                    value="{{ $student->name }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][prelim]"
                                    value="{{ $studentGrades[$student->studentID]['Prelim'] }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][midterm]"
                                    value="{{ $studentGrades[$student->studentID]['Midterm'] }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][semi_finals]"
                                    value="{{ $studentGrades[$student->studentID]['Semi-Finals'] }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][final]"
                                    value="{{ $studentGrades[$student->studentID]['Finals'] }}">
                                <input type="hidden" name="grades[{{ $student->studentID }}][remarks]"
                                    value="{{ $studentGrades[$student->studentID]['Remarks'] }}">
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Lock In Button -->
                @if ($finalGrades->isEmpty() || !$finalGrades->first()->status)
                    <button class="save-btn" style="margin-top: 10px"><i class="fa-solid fa-unlock"></i> Lock In
                        Grades</button>
                @endif
            </form>

            @if ($finalGrades->isNotEmpty() && $finalGrades->first()->status)
                <form action="{{ route('finalgrade.unlock') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="margin: 10px 10px 0 0"
                        onclick="return confirm('Are you sure you want to unlock grades?')">
                        <i class="fa-solid fa-lock"></i> Unlock Grades
                    </button>
                </form>

                <form action="{{ route('finalgrade.save') }}" method="POST" style="display:inline;">
                    @csrf
                    @method('POST')
                    <button type="submit" class="save-btn">
                        <i class="fa-solid fa-file-export"></i> Submit
                    </button>
                </form>
            @endif
        </div>

        <style>
            /* Styling scoped to the grade-sheet-container to avoid affecting other elements */
            .grade-sheet-container .raw-column {
                background-color: var(--hover-background-color);
                /* Light red/pink */
                width: 10%;
                /* Dark red text */
                font-weight: bold;
                border-left: 2px dashed var(--color-red);
                border-right: 2px dashed var(--color-red);
                transition: background-color 0.3s ease-in-out;
            }
        </style>

        <script>
            function toggleRawColumns() {
                let columns = document.querySelectorAll('.raw-column');
                let button = document.querySelector('.toggle-btn');
                let icon = button.querySelector('i');
                let isHidden = columns[0].style.display === 'none';

                columns.forEach(col => {
                    col.style.display = isHidden ? '' : 'none';
                });

                button.innerHTML = isHidden ?
                    '<i class="fa-solid fa-eye-slash"></i> Hide Raw Columns' :
                    '<i class="fa-solid fa-eye"></i> Show Raw Columns';
            }
        </script>






    </div>
    {{-- end of dashboard --}}


























    <!-- JavaScript to enable/disable delete button -->
    <script>
        function toggleDeleteButton(classId) {
            const checkbox = document.getElementById("confirmDelete" + classId);
            const deleteBtn = document.getElementById("deleteBtn" + classId);

            if (checkbox.checked) {
                deleteBtn.disabled = false;
                deleteBtn.classList.remove("disabled-btn");
            } else {
                deleteBtn.disabled = true;
                deleteBtn.classList.add("disabled-btn");
            }
        }
    </script>

    <!-- CSS for the disabled delete button -->
    <style>
        .disabled-btn {
            background-color: #ccc !important;
            /* Greyed out */
            cursor: not-allowed !important;
            /* Unclickable */
            opacity: 0.6;
            /* Lower visibility */
        }
    </style>


    <script>
        // Open the Edit Class modal
        function openEditClassModal(classId) {
            // Select the modal using the classId
            var modal = document.getElementById('editClassModal' + classId);
            modal.style.display = 'flex'; // Show the modal
        }

        // Close the Edit Class modal
        function closeEditClassModal(classId) {
            // Select the modal using the classId
            var modal = document.getElementById('editClassModal' + classId);
            modal.style.display = 'none'; // Hide the modal
        }

        function openDeleteClassModal(classId) {
            const modal = document.getElementById('deleteClassModal' + classId);
            modal.style.display = 'flex';
        }

        function closeDeleteClassModal(classId) {
            const modal = document.getElementById('deleteClassModal' + classId);
            modal.style.display = 'none';
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>


















    <!-- Add New Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Student</h2>
                <button class="close" onclick="closeAddStudentModal()">&times;</button>
            </div>

            <form action="{{ route('class.addstudent', $class->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="info-container">
                        <label for="student_id">Student ID</label>
                        <input type="text" id="student_id" name="student_id" class="form-control" required readonly>
                    </div>
                    <div class="info-container">
                        <label for="student">Student</label>
                        <select id="student" name="name" class="form-control" onchange="fillStudentInfo()"
                            required>
                            <option value="" disabled selected>Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" data-name="{{ $student->name }}"
                                    data-email="{{ $student->email }}" data-department="{{ $student->department }}"
                                    data-id="{{ $student->id }}">
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="info-container">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required readonly>
                    </div>
                    <div class="info-container">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" class="form-control" required readonly>
                    </div>
                    <div class="info-container">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department" class="form-control" required readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddStudentModal()">Cancel</button>
                    <button type="submit" class="save-btn"><i class="fa-solid fa-file-arrow-up"></i> Add
                        Student</button>
                </div>

            </form>
        </div>
    </div>








@endsection







<style>
    table td {}

    .grades-container td {
        padding: 0;
    }

    .content-container {
        display: flex;
        width: 100%;

    }

    .content-container .cell-content {
        flex-grow: 1;
        width: 100%;
        padding: 5px;
        border: 1px solid var(--color8);
    }

    .cell-content input {
        width: 100%;
        text-align: center;
        background-color: var(--ckcm-color2);
        color: var(--color1);
        border: 1px solid var(--ckcm-color2);
        padding: 5px;

    }
</style>






<style>
    .calculation-base-container {
        display: flex;
        width: 100%;
        margin: 10px 0;
        gap: 10px;
        border: 1px solid var(--color7);
        background: var(--ckcm-color1)
    }

    .calculation-container {
        padding: 10px;
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 10px;
        gap: 5px;
    }


    .calculation-container h4 {
        color: var(--ckcm-color4);
        margin-bottom: 5px;
    }

    .calculation-content {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .calculation-content label {
        color: var(--color3);
    }

    .calculation-content input {
        background-color: var(--ckcm-color2);
        padding: 5px;
        border: 1px solid var(--ckcm-color2);
        color: var(--color2);
    }
</style>

















<style>
    .dashboard {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 10px;
    }

    .dashboard h1 {
        color: var(--ckcm-color4);
    }

    .dashboard h2 {
        color: var(--color5);
    }

    .dashboard p {
        font-size: 1.2rem;
        margin: 5px 0;
    }

    .dashboard a {

        text-decoration: none;
        font-weight: bold;
    }

    .container {
        display: flex;
        flex-direction: column;
    }



    h3 {
        color: var(--ckcm-color4)
    }
</style>
