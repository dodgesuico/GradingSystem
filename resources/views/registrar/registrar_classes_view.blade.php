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

        @php
            $isDean = str_contains(auth()->user()->role, 'dean');
            $isNotInstructor = isset($class->instructor) && $class->instructor !== auth()->user()->name;

            // ✅ Ensure we only hide if there are grades AND all are locked
            $allLocked =
                $finalGrades->isNotEmpty() &&
                $finalGrades
                    ->groupBy('department')
                    ->every(fn($grades) => $grades->where('status', 'Locked')->count() === $grades->count());

            $shouldHide = $allLocked || ($isDean && $isNotInstructor);
        @endphp

        @if (!$shouldHide)

            <div id="grade-content">


                <div style="display:flex; flex-direction:row; justify-content:space-between">
                    <h2 style="margin:10px 0;">Class Students List</h2>

                    @if ((Auth::user() && str_contains(Auth::user()->role, 'dean')) || str_contains(Auth::user()->role, 'instructor'))
                        <button class="add-btn" onclick="openAddStudentModal()">
                            <i class="fa-solid fa-plus"></i> Add Student
                        </button>
                    @endif
                </div>

                <div class="container" style="max-height: 300px; overflow-y: auto; border-bottom: var(--color8) 4px solid;">
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
                                    <th>Gender</th>
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
                                        <td>{{ $classes_students->gender }}</td>
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

                <h2 class="grading-title" style="margin: 20px 0;">Grading & Scores</h2>

                <div class="grading-tabs">
                    @foreach (['Prelim', 'Midterm', 'Semi-Finals', 'Finals'] as $term)
                        <button type="button" class="grading-tab" onclick="showGradingTab('{{ $term }}')"
                            id="tab-{{ $term }}">
                            {{ $term }}
                        </button>
                    @endforeach
                </div>

                @foreach (['Prelim', 'Midterm', 'Semi-Finals', 'Finals'] as $term)
                    <div id="grading-content-{{ $term }}" class="grading-content"
                        style="display: none; background-color: var(--color9b); padding: 20px; border: 1px solid var(--color7);">

                        <h3>Grading</h3>
                        <form action="{{ route('class.addPercentageAndScores', ['class' => $class->id]) }}" method="POST">
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
                                            <input type="number" id="{{ $key }}_percentage_{{ $term }}"
                                                name="{{ $key }}_percentage[{{ $term }}]"
                                                value="{{ old($key . '_percentage.' . $term, $percentageValue) }}"
                                                min="0" max="100" required>
                                        </div>
                                        <div class="calculation-content">
                                            <label>Total Score</label>
                                            <input type="number" id="{{ $key }}_total_score_{{ $term }}"
                                                name="{{ $key }}_total_score[{{ $term }}]"
                                                value="{{ old($key . '_total_score.' . $term, optional(optional($percentage)->where('periodic_term', $term)->first())->{$key . '_total_score'} ?? '') }}"
                                                min="0">
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                // Function to restore input value from localStorage
                                                function restoreValue(inputId) {
                                                    let storedValue = localStorage.getItem(inputId);
                                                    if (storedValue !== null) {
                                                        document.getElementById(inputId).value = storedValue;
                                                    }
                                                }

                                                // Function to store input value in localStorage
                                                function storeValue(event) {
                                                    localStorage.setItem(event.target.id, event.target.value);
                                                }

                                                // Target each specific input by ID
                                                let percentageInput = document.getElementById("{{ $key }}_percentage_{{ $term }}");
                                                let totalScoreInput = document.getElementById("{{ $key }}_total_score_{{ $term }}");

                                                // Restore saved values on page load
                                                if (percentageInput) restoreValue(percentageInput.id);
                                                if (totalScoreInput) restoreValue(totalScoreInput.id);

                                                // Store values when user types
                                                if (percentageInput) percentageInput.addEventListener("input", storeValue);
                                                if (totalScoreInput) totalScoreInput.addEventListener("input", storeValue);
                                            });
                                        </script>



                                    </div>
                                @endforeach


                            </div>
                            <button type="submit" class="save-btn" style="margin: 5px 0 0 0"><i
                                    class="fa-solid fa-floppy-disk"></i> Save Grading and Total Score</button>
                            <em style="color: var(--color5); margin-left: 10px;">Note: Do not forget to save first
                                before entering the student scores.</em>
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
                                                            $transmutedGrade = $transmutedGradeEntry->transmuted_grade;
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
                                                                id="score_{{ $student ? $student->studentID : 'unknown' }}_{{ $field }}_{{ $term }}"
                                                                name="scores[{{ $student ? $student->studentID : '' }}][{{ $field }}]"
                                                                value="{{ $score && $score->$field !== null ? number_format($score->$field, 2) : '0.00' }}"
                                                                min="0" step="0.01">
                                                        </div>

                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                document.querySelectorAll("input[id^='score_']").forEach(input => {
                                                                    let savedValue = localStorage.getItem(input.id);
                                                                    if (savedValue !== null) {
                                                                        input.value = parseFloat(savedValue).toFixed(2); // Ensure 2 decimal places
                                                                    }

                                                                    input.addEventListener("input", function() {
                                                                        let value = parseFloat(this.value);
                                                                        if (!isNaN(value)) {
                                                                            localStorage.setItem(input.id, value); // Save raw value
                                                                        }
                                                                    });

                                                                    input.addEventListener("blur", function() {
                                                                        let value = parseFloat(this.value);
                                                                        if (!isNaN(value)) {
                                                                            this.value = value.toFixed(2); // Format on blur
                                                                            localStorage.setItem(input.id, this.value);
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>



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
                            <em style="color: var(--color5); margin-left: 10px;">Note: Do not exceed with the total
                                score.</em>
                        </form>
                    </div>

                    <script>
                        function showGradingTab(term) {
                            document.querySelectorAll('.grading-content').forEach(content => {
                                content.style.display = 'none';
                            });
                            document.querySelectorAll('.grading-tab').forEach(tab => {
                                tab.classList.remove('active');
                            });
                            document.getElementById('grading-content-' + term).style.display = 'block';
                            document.getElementById('tab-' + term).classList.add('active');
                        }

                        document.addEventListener("DOMContentLoaded", function() {
                            const firstTab = document.querySelector(".grading-tabs button");
                            if (firstTab) {
                                showGradingTab(firstTab.innerText);
                            }
                        });
                    </script>

                    <style>
                        .grading-tab {
                            border: 0;
                            color: var(--color6);
                            padding: 10px;
                            border-radius: 0;
                            font-size: 1.3rem;
                        }

                        .grading-tab.active {
                            border: 1px solid var(--color7);
                            border-bottom: 0;
                            background-color: var(--color9b);
                            color: var(--ckcm-color4);
                            font-weight: bold;
                        }
                    </style>
                @endforeach
                {{-- end of students and scroll --}}


            </div>
        @endif















        <!-- Add New Student Modal -->
        <div id="addStudentModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Student</h2>
                    <button class="close" onclick="closeAddStudentModal()">&times;</button>
                </div>

                <form style="display:flex;  justify-content: space-between; flex-direction: column; gap: 10px;"
                    id="csvUploadForm" action="{{ route('students.import', $classes->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="students_csv" name="students_csv" accept=".csv" required>
                    <button type="submit" class="save-btn">
                        <i class="fa-solid fa-file-arrow-up"></i> Add Multiple Students
                    </button>
                </form>

                <p style="color: var(--color5); text-align:  center; margin: 10px 0;">~ or add student individually ~</p>

                <form action="{{ route('class.addstudent', $class->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="info-container">
                            <label for="studentSearch">Find Student</label>
                            <input type="text" id="studentSearch" class="form-control"
                                placeholder="Search for a student..." oninput="filterStudents()">
                            <div id="studentDropdown" class="dropdown-menu"></div>

                        </div>
                        <div class="info-container">
                            <label for="student_id">Student ID</label>
                            <input type="text" id="student_id" name="student_id" class="form-control" required
                                readonly>
                        </div>

                        <div class="info-container">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" required readonly>
                        </div>
                        <div class="info-container">
                            <label for="gender">Gender</label>
                            <input type="text" id="gender" name="gender" class="form-control" required readonly>
                        </div>
                        <div class="info-container">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" class="form-control" required readonly>
                        </div>
                        <div class="info-container">
                            <label for="department">Department</label>
                            <input type="text" id="department" name="department" class="form-control" required
                                readonly>
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
                    document.getElementById('gender').value = selectedOption.getAttribute('data-gender');
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

            function filterStudents() {
                let input = document.getElementById("studentSearch").value.toLowerCase();
                let dropdown = document.getElementById("studentDropdown");
                dropdown.innerHTML = ""; // Clear previous results

                if (input.trim() === "") {
                    dropdown.style.display = "none";
                    return;
                }

                let students = {!! json_encode($students) !!}; // Use existing Blade variable
                let filtered = students.filter(student =>
                    student.name.toLowerCase().includes(input) ||
                    student.email.toLowerCase().includes(input) ||
                    student.studentID.toString().includes(input) || // 🔹 Allow searching by ID
                    student.department.toLowerCase().includes(input) // 🔹 Allow searching by Department
                );

                if (filtered.length === 0) {
                    dropdown.style.display = "none";
                    return;
                }

                filtered.forEach(student => {
                    let option = document.createElement("div");
                    option.classList.add("dropdown-item");
                    option.textContent =
                        `${student.studentID} - ${student.name} (${student.department})`; // 🔹 Show ID & Department
                    option.onclick = function() {
                        document.getElementById("studentSearch").value = student.name;
                        document.getElementById("student_id").value = student.studentID;
                        document.getElementById("name").value = student.name;
                        document.getElementById("gender").value = student.gender;
                        document.getElementById("email").value = student.email;
                        document.getElementById("department").value = student.department;
                        dropdown.style.display = "none";
                    };
                    dropdown.appendChild(option);
                });

                dropdown.style.display = "block";
            }
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













        <h2 style="margin: 10px 0; ">Grades</h2>

        @if ($finalGrades->where('status', 'Locked')->isEmpty() && $classes_student->isNotEmpty() && Auth::user()->name === $classes->instructor)
            <form action="{{ route('initialize.grade') }}" method="POST" style="display:inline;">
                @csrf
                @method('POST')

                @foreach ($classes_student->groupBy('department') as $department => $studentsByDepartment)
                    @foreach ($studentsByDepartment as $student)
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
                    @endforeach
                @endforeach

                <button type="submit" class="btn btn-danger" style="margin: 5px 0">
                    <i class="fa-brands fa-osi"></i> Initialize
                </button>

                <p style="color: var(--color-red)">Initialize first before locking the grades</p>
            </form>
        @endif

        <div style="margin-bottom: 10px;">
            <button onclick="toggleRawColumns()" class="toggle-btn">
                <i class="fa-solid fa-eye"></i> Show Raw Columns
            </button>
        </div>


        <div class="grade-sheet-container">
            @php
                $loggedInUserDepartment = Auth::user()->department;
                $isRegistrar = in_array('registrar', explode(',', Auth::user()->role)); // Check if user is a registrar
            @endphp


            @foreach ($classes_student->groupBy('department') as $department => $studentsByDepartment)
                @php
                    // Check if any student in this department has registrar_status 'Pending'
                    $hasPendingApproval = $finalGrades->where('department', $department)
                                                    ->contains('registrar_status', 'Pending');
                @endphp

                @if (
                        (!$isRegistrar && ($loggedInUserDepartment === 'N/A' || $department === $loggedInUserDepartment || (isset($class->instructor) && $class->instructor === auth()->user()->name)))
                        || ($isRegistrar && $hasPendingApproval) // Registrar can only see departments with pending approvals
                    )

                    <h3 style="margin: 10px 0 10px 0 ;">{{ $department }} Department</h3>

                    @php
                        $gradesByDepartment = $finalGrades->where('department', $department);
                    @endphp

                    <h3 style="color: var(--color6); margin-bottom:10px; display:flex; justify-content: space-between;">

                        <div class="grade-sheet-table-header">
                            Status:
                            <strong>
                                {{ $gradesByDepartment->where('status', 'Locked')->isNotEmpty() ? 'Locked' : 'Not Locked Yet' }}
                            </strong>
                        </div>

                        <div class="grade-sheet-table-header">
                            Submit to Dean Status:
                            <strong>
                                @if ($gradesByDepartment->isNotEmpty())
                                    @if ($gradesByDepartment->first()->submit_status == 'Submitted')
                                        Submitted
                                    @elseif ($gradesByDepartment->first()->submit_status == 'Returned')
                                        Returned
                                    @else
                                        Pending
                                    @endif
                                @else
                                    Pending
                                @endif
                            </strong>
                        </div>

                        <div class="grade-sheet-table-header">
                            Dean Approval:
                            <strong>
                                @if ($gradesByDepartment->isNotEmpty())
                                    @if ($gradesByDepartment->first()->dean_status == 'Confirmed')
                                        Confirmed
                                    @elseif ($gradesByDepartment->first()->dean_status == 'Returned')
                                        Returned
                                    @else
                                        Pending
                                    @endif
                                @else
                                    Pending
                                @endif
                            </strong>
                        </div>

                        <div class="grade-sheet-table-header">
                            Registrars Approval:
                            <strong>
                                @if ($gradesByDepartment->isNotEmpty())
                                    @if ($gradesByDepartment->first()->registrar_status == 'Approved')
                                        Approved
                                    @elseif ($gradesByDepartment->first()->registrar_status == 'Rejected')
                                        Rejected
                                    @else
                                        Pending
                                    @endif
                                @else
                                    Pending
                                @endif
                            </strong>
                        </div>
                    </h3>


                    <form action="{{ route('finalgrade.lock') }}" method="POST">
                        @csrf
                        @method('POST')
                        <table>
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Department</th>
                                    <th>Prelim</th>
                                    <th class="raw-column" style="display: none;">Midterm (Raw)</th>
                                    <th>Midterm</th>
                                    <th class="raw-column" style="display: none;">Semi-Finals (Raw)</th>
                                    <th>Semi-Finals</th>
                                    <th class="raw-column" style="display: none;">Finals (Raw)</th>
                                    <th>Final</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentsByDepartment as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->department }}</td>
                                        <td>{{ number_format($studentGrades[$student->studentID]['Prelim'], 2) }}</td>
                                        <td class="raw-column" style="display: none;">
                                            {{ number_format($studentGrades[$student->studentID]['Midterm Raw'], 2) }}
                                        </td>
                                        <td>{{ number_format($studentGrades[$student->studentID]['Midterm'], 2) }}</td>
                                        <td class="raw-column" style="display: none;">
                                            {{ number_format($studentGrades[$student->studentID]['Semi-Finals Raw'], 2) }}
                                        </td>
                                        <td>{{ number_format($studentGrades[$student->studentID]['Semi-Finals'], 2) }}</td>
                                        <td class="raw-column" style="display: none;">
                                            {{ number_format($studentGrades[$student->studentID]['Finals Raw'], 2) }}
                                        </td>
                                        <td>{{ number_format($studentGrades[$student->studentID]['Finals'], 2) }}</td>
                                        <td><strong>{{ $studentGrades[$student->studentID]['Remarks'] }}</strong></td>

                                        <!-- Hidden inputs -->
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

                        @php
                            // Get only the grades for this specific department
                            $departmentGrades = $finalGrades->where('department', $department);

                            // Check if this department has ALL grades locked
                            $isDepartmentLocked =
                                $departmentGrades->isNotEmpty() &&
                                $departmentGrades->where('status', 'Locked')->count() === $departmentGrades->count();

                            // Check if the logged-in user is a dean and not the instructor
                            $isDean = str_contains(auth()->user()->role, 'dean');

                            // Ensure we get the instructor's name from the first class record
                            $classInstructor = $classes ? $classes->instructor : null;

                            // Compare instructor name safely
                            $isNotInstructor =
                                trim(strtolower($classInstructor)) !== trim(strtolower(auth()->user()->name));
                        @endphp

                        <!-- Lock In Button (for this department only) -->
                        @if (!$isDepartmentLocked && !($isDean && $isNotInstructor))
                            <button class="save-btn" style="margin-top: 10px">
                                <i class="fa-solid fa-unlock"></i> Lock In {{ $department }} Grades
                            </button>
                        @endif

                    </form>
                    <br>
                @endif


                {{-- submit and lock grades section --}}
                @if (isset($gradesByDepartment) && $gradesByDepartment->isNotEmpty() && $gradesByDepartment->first()->status)

                    <!-- Unlock Grades (Only for this department) -->
                    @if (
                            $gradesByDepartment->isNotEmpty() &&
                            (empty($gradesByDepartment->first()->submit_status) || $gradesByDepartment->first()->submit_status == 'Returned')
                        )
                        @if (Auth::check() &&
                            in_array('instructor', explode(',', Auth::user()->role)) &&
                            Auth::user()->name === $gradesByDepartment->first()->instructor
                        )
                            @php
                                $deanStatus = $gradesByDepartment->first()->dean_status;
                            @endphp














                            {{-- instructors view --}}
                            @if ($deanStatus == 'Returned')
                                <!-- ❌ Instructor sees notification if grades are returned -->
                                <div class="alert alert-danger" style="margin-top: 10px;">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    The final grades have been <strong> returned by the Dean</strong>. Please review and resubmit.
                                </div>

                                <!-- 📝 Show Dean's Comment -->
                                @if (!empty($gradesByDepartment->first()->dean_comment))
                                <div class="alert alert-warning" style="margin-top: 10px; background-color: var(--color9b); text-align: left; font-size: 1.2rem; padding: 10px; border: 1px dashed var(--ckcm-color4);">
                                    <strong style="color: var(--ckcm-color4);">
                                        <i class="fa-solid fa-comment"></i> Dean's Comment:
                                    </strong>
                                    <p class="comment-text" style="color: var(--color5) ; padding-left: 5px; ">
                                        <span class="short-text">{{ Str::limit($gradesByDepartment->first()->dean_comment, 100) }}</span> <!-- Show first 100 chars -->
                                        <span class="full-text" style="display: none;">{{ $gradesByDepartment->first()->dean_comment }}</span>
                                        <a style="text-decoration: underline;" href="javascript:void(0);" class="see-more" onclick="toggleComment()">See more</a>
                                    </p>


                                </div>

                                <!-- Inline CSS -->
                                <style>
                                    .see-more {
                                        color: gray;

                                        cursor: pointer;
                                        font-size: 1.1rem;
                                        padding-left: 10px;
                                        font-weight: 100;
                                    }
                                </style>

                                <!-- JavaScript -->
                                <script>
                                    function toggleComment() {
                                        var shortText = document.querySelector('.short-text');
                                        var fullText = document.querySelector('.full-text');
                                        var seeMoreLink = document.querySelector('.see-more');

                                        // Toggle the display between short and full text
                                        if (fullText.style.display === 'none') {
                                            fullText.style.display = 'inline';
                                            shortText.style.display = 'none';
                                            seeMoreLink.innerHTML = 'See less';  // Change link text to 'See less'
                                        } else {
                                            fullText.style.display = 'none';
                                            shortText.style.display = 'inline';
                                            seeMoreLink.innerHTML = 'See more';  // Revert back to 'See more'
                                        }
                                    }
                                </script>

                                @endif
                            @endif



                            @php
                                $registrarStatus = $gradesByDepartment->first()->registrar_status;
                            @endphp

                            {{-- instructors view --}}
                            @if ($registrarStatus == 'Rejected')
                                <!-- ❌ Instructor sees notification if grades are returned -->
                                <div class="alert alert-danger" style="margin-top: 10px;">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    The final grades have been <strong> rejected by the Registrar</strong>. Please review and resubmit.
                                </div>

                                <!-- 📝 Show Registrar's Comment -->
                                @if (!empty($gradesByDepartment->first()->registrar_comment))
                                <div class="alert alert-warning" style="margin-top: 10px; background-color: var(--color9b); text-align: left; font-size: 1.2rem; padding: 10px; border: 1px dashed var(--ckcm-color4);">
                                    <i class="fa-solid fa-comment"></i>
                                    <strong style="color: var(--ckcm-color4);">
                                        Registrar's Comment:
                                    </strong>
                                    <p class="comment-text" style="color: var(--color5); padding-left: 5px;">
                                        <span class="short-text">{{ Str::limit($gradesByDepartment->first()->registrar_comment, 100) }}</span> <!-- Show first 100 chars -->
                                        <span class="full-text" style="display: none;">{{ $gradesByDepartment->first()->registrar_comment }}</span>
                                        <a style="text-decoration: underline;" href="javascript:void(0);" class="see-more" onclick="toggleCommentRegistrar()">See more</a>
                                    </p>
                                </div>

                                <!-- Inline CSS -->
                                <style>
                                    .see-more {
                                        color: gray;
                                        cursor: pointer;
                                        font-size: 1.1rem;
                                        padding-left: 10px;
                                        font-weight: 100;
                                    }
                                </style>

                                <!-- JavaScript -->
                                <script>
                                    function toggleCommentRegistrar() {
                                        var shortText = document.querySelectorAll('.short-text')[1]; // Target the second short-text (Registrar's Comment)
                                        var fullText = document.querySelectorAll('.full-text')[1]; // Target the second full-text (Registrar's Comment)
                                        var seeMoreLink = document.querySelectorAll('.see-more')[1]; // Target the second see-more link (Registrar's Comment)

                                        // Toggle the display between short and full text
                                        if (fullText.style.display === 'none') {
                                            fullText.style.display = 'inline';
                                            shortText.style.display = 'none';
                                            seeMoreLink.innerHTML = 'See less';  // Change link text to 'See less'
                                        } else {
                                            fullText.style.display = 'none';
                                            shortText.style.display = 'inline';
                                            seeMoreLink.innerHTML = 'See more';  // Revert back to 'See more'
                                        }
                                    }
                                </script>

                                @endif
                            @endif



















                            <form action="{{ route('finalgrade.unlock') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="department" value="{{ $department }}">
                                <input type="hidden" name="classID" value="{{ $gradesByDepartment->first()->classID }}">

                                <button type="submit" class="btn btn-danger" style="margin: 10px 10px 0 0"
                                    onclick="return confirm('Are you sure you want to unlock grades for {{ $department }}?')">
                                    <i class="fa-solid fa-lock-open"></i> Unlock Grades ({{ $department }})
                                </button>
                            </form>

                            <form action="{{ route('finalgrade.save') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="department" value="{{ $department }}">
                                <input type="hidden" name="classID" value="{{ $gradesByDepartment->first()->classID }}">

                                <button type="submit" class="save-btn">
                                    <i class="fa-solid fa-file-export"></i> Submit to Dean ({{ $department }})
                                </button>
                            </form>
                        @endif
                    @endif

                    {{-- end of submit and lock grades section --}}


                    {{-- dean approval section --}}
                    @if (
                        $gradesByDepartment->isNotEmpty() &&
                            $gradesByDepartment->first()->submit_status == 'Submitted' &&
                            $gradesByDepartment->first()->dean_status != 'Confirmed')
                        @if (Auth::check() && in_array('dean', explode(',', Auth::user()->role)))
                            @php
                                $userDepartment = Auth::user()->department; // Get Dean's department
                            @endphp

                            @php
                                $registrarStatus = $gradesByDepartment->first()->registrar_status;
                            @endphp

                            @if ($registrarStatus == 'Rejected')
                                <!-- ❌ Instructor sees notification if grades are returned -->
                                <div class="alert alert-danger" style="margin-top: 10px;">
                                    <i class="fa-solid fa-exclamation-circle"></i>
                                    The final grades have been <strong>returned by the Registrar</strong>. Please review and resubmit.
                                </div>

                                <!-- 📝 Show Dean's Comment -->
                                @if (!empty($gradesByDepartment->first()->registrar_comment))
                                <div class="alert alert-warning" style="margin-top: 10px; background-color: var(--color9b); text-align: left; font-size: 1.2rem; padding: 10px; border: 1px dashed var(--ckcm-color4);">
                                    <i class="fa-solid fa-comment"></i>
                                    <strong style="color: var(--ckcm-color4);">
                                        Registrar's Comment:
                                    </strong>
                                    <p class="comment-text" style="color: var(--color5); padding-left: 5px;">
                                        <span class="short-text">{{ Str::limit($gradesByDepartment->first()->registrar_comment, 100) }}</span>
                                        <span class="full-text" style="display: none;">{{ $gradesByDepartment->first()->registrar_comment }}</span>
                                        <a style="text-decoration: underline;" href="javascript:void(0);" class="see-more">See more</a>
                                    </p>
                                </div>

                                <style>
                                    .see-more {
                                        color: gray;
                                        cursor: pointer;
                                        font-size: 1.1rem;
                                        padding-left: 10px;
                                        font-weight: 100;
                                    }
                                </style>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        document.querySelectorAll('.see-more').forEach(link => {
                                            link.addEventListener('click', function () {
                                                const commentText = this.closest('.comment-text');
                                                const shortText = commentText.querySelector('.short-text');
                                                const fullText = commentText.querySelector('.full-text');

                                                const isExpanded = fullText.style.display === 'inline';

                                                shortText.style.display = isExpanded ? 'inline' : 'none';
                                                fullText.style.display = isExpanded ? 'none' : 'inline';
                                                this.textContent = isExpanded ? 'See more' : 'See less';
                                            });
                                        });
                                    });
                                </script>


                                @endif
                            @endif

                            @if ($department == $userDepartment)
                                <h4 style="margin: 10px 0; color: var(--ckcm-color4); font-size: 1.2rem;">Dean's Decision
                                    for {{ $department }}</h4>
                                    <form action="{{ route('finalgrade.decision') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="department" value="{{ $department }}">
                                        <input type="hidden" name="classID" value="{{ $gradesByDepartment->first()->classID }}">

                                        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 10px; padding-left: 10px;">
                                            <style>
                                                .custom-radio-group {
                                                    display: flex;
                                                    gap: 20px;
                                                    align-items: center;
                                                }

                                                .custom-radio {
                                                    display: flex;
                                                    align-items: center;
                                                    gap: 8px;
                                                    cursor: pointer;
                                                    position: relative;
                                                    font-weight: 500;
                                                    user-select: none;
                                                }

                                                .custom-radio input[type="radio"] {
                                                    opacity: 0;
                                                    position: absolute;
                                                    cursor: pointer;
                                                }

                                                .custom-radio span.radio-mark {
                                                    height: 18px;
                                                    width: 18px;
                                                    border-radius: 50%;
                                                    border: 2px solid #ccc;
                                                    display: inline-block;
                                                    transition: border-color 0.3s, background-color 0.3s;
                                                    position: relative;
                                                }

                                                .custom-radio input[type="radio"]:checked + span.radio-mark {
                                                    border-color: #4CAF50;
                                                    background-color: #4CAF50;
                                                }

                                                .custom-radio.green input[type="radio"]:checked + span.radio-mark {
                                                    border-color: var(--color-green);
                                                    background-color: var(--color-green);
                                                }

                                                .custom-radio.red input[type="radio"]:checked + span.radio-mark {
                                                    border-color: var(--color-red);
                                                    background-color: var(--color-red);
                                                }

                                                .custom-radio span.radio-mark::after {
                                                    content: "";
                                                    position: absolute;
                                                    top: 3px;
                                                    left: 3px;
                                                    width: 8px;
                                                    height: 8px;
                                                    border-radius: 50%;
                                                    background: white;
                                                    display: none;
                                                }

                                                .custom-radio input[type="radio"]:checked + span.radio-mark::after {
                                                    display: block;
                                                }

                                                .custom-radio-label {
                                                    color: inherit;
                                                }

                                                .custom-radio.green .custom-radio-label {
                                                    color: var(--color-green);
                                                }

                                                .custom-radio.red .custom-radio-label {
                                                    color: var(--color-red);
                                                }

                                                .comment-box {
                                                    display: none;
                                                    margin-bottom: 10px;
                                                }
                                            </style>

                                            <div class="custom-radio-group">
                                                <label class="custom-radio green">
                                                    <input type="radio" name="dean_status" value="Confirmed" required>
                                                    <span class="radio-mark"></span>
                                                    <span class="custom-radio-label">✔️ Confirmed</span>
                                                </label>

                                                <label class="custom-radio red">
                                                    <input type="radio" name="dean_status" value="Returned" required>
                                                    <span class="radio-mark"></span>
                                                    <span class="custom-radio-label">❌ Returned</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="comment-box-wrapper">
                                            <div class="comment-box">
                                                <textarea style="background: var(--color9b); color: var(--color1); padding: 5px; width: 100%; border: 1px dashed var(--color6); padding: 10px;"
                                                    name="dean_comment" rows="3" class="form-control" placeholder="Add a comment (optional, only if returned)..."></textarea>
                                            </div>
                                        </div>

                                        <button type="submit" class="save-btn">
                                            <i class="fa-solid fa-check"></i> Submit Decision for {{ $department }}
                                        </button>
                                    </form>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            document.querySelectorAll('form').forEach(form => {
                                                const radios = form.querySelectorAll('input[name="dean_status"]');
                                                const commentBox = form.querySelector('.comment-box');

                                                radios.forEach(radio => {
                                                    radio.addEventListener('change', function () {
                                                        if (this.value === 'Returned') {
                                                            commentBox.style.display = 'block';
                                                        } else {
                                                            commentBox.style.display = 'none';
                                                        }
                                                    });

                                                    // Show on load if "Returned" is pre-selected
                                                    if (radio.checked && radio.value === 'Returned') {
                                                        commentBox.style.display = 'block';
                                                    }
                                                });
                                            });
                                        });
                                    </script>

                            @endif
                        @endif
                    @endif
                    {{-- end of dean approval --}}























                    {{-- instructors notification --}}
                    @if (
                            $gradesByDepartment->isNotEmpty() &&
                            $gradesByDepartment->first()->submit_status == 'Submitted' &&
                            $gradesByDepartment->first()->dean_status == 'Confirmed'
                        )
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                                $classInstructor = $gradesByDepartment->first()->instructor; // 🔥 Ensure this field exists in DB
                            @endphp


                            @if (in_array('instructor', explode(',', $user->role)) && $user->name === $classInstructor)
                                <!-- ✅ Instructor sees notification instead of submit button -->
                                <div class="alert alert-success" style="margin-top: 10px;">
                                    <i class="fa-solid fa-check-circle"></i>
                                    The final grades have been <strong>approved by the Dean</strong> of {{ $department }}.
                                </div>
                                 <!-- 📝 Show Dean's Comment -->
                                 @if (!empty($gradesByDepartment->first()->dean_comment))
                                    <div class="alert alert-warning" style="margin-top: 10px;">
                                        <i class="fa-solid fa-comment"></i>
                                        <strong>Dean's Comment:</strong> {{ $gradesByDepartment->first()->dean_comment }}
                                    </div>
                                @endif

                                @if (
                                        $gradesByDepartment->first()->registrar_status == 'Approved'
                                    )
                                    <!-- ✅ Instructor sees notification instead of submit button -->
                                    <div class="alert alert-success" style="margin-top: 10px;">
                                        <i class="fa-solid fa-check-circle"></i>
                                        The final grades have been approved by the <strong> Registrar </strong>.
                                    </div>
                                    <!-- 📝 Show Dean's Comment -->
                                    @if (!empty($gradesByDepartment->first()->dean_comment))
                                        <div class="alert alert-warning" style="margin-top: 10px;">
                                            <i class="fa-solid fa-comment"></i>
                                            <strong>Registrar's Comment:</strong> {{ $gradesByDepartment->first()->dean_comment }}
                                        </div>
                                    @endif
                                @elseif (
                                            $gradesByDepartment->first()->registrar_status == 'Rejected'
                                        )
                                        <!-- ✅ Instructor sees notification instead of submit button -->
                                        <div class="alert alert-success" style="margin-top: 10px;">
                                            <i class="fa-solid fa-check-circle"></i>
                                            The final grades have been approved by the <strong> Registrar </strong>.
                                        </div>
                                        <!-- 📝 Show Dean's Comment -->
                                        @if (!empty($gradesByDepartment->first()->dean_comment))
                                            <div class="alert alert-warning" style="margin-top: 10px;">
                                                <i class="fa-solid fa-comment"></i>
                                                <strong>Registrar's Comment:</strong> {{ $gradesByDepartment->first()->dean_comment }}
                                            </div>
                                        @endif
                                @endif


                            @endif
                        @endif
                    @endif
                    {{-- instructors notification end --}}

















                    {{-- dean submit to registrar start --}}
                    @if (
                            $gradesByDepartment->isNotEmpty() &&
                            $gradesByDepartment->first()->submit_status == 'Submitted' &&
                            $gradesByDepartment->first()->dean_status == 'Confirmed' &&
                            $gradesByDepartment->first()->registrar_status != 'Pending' &&
                            $gradesByDepartment->first()->registrar_status != 'Approved'
                        )
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                            @endphp


                            <!-- ✅ Allow submission ONLY if the user is a dean AND their department matches -->
                            @if (in_array('dean', explode(',', $user->role)) && $user->department === $department)
                                <form action="{{ route('finalgraderegistrar.save') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="department" value="{{ $department }}">
                                    <input type="hidden" name="classID" value="{{ $gradesByDepartment->first()->classID }}">

                                    <button type="submit" class="save-btn">
                                        <i class="fa-solid fa-file-export"></i> Submit to Registrar
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                    {{-- dean submit to registrar end --}}

                @endif















                @if (
                        ($isRegistrar && $hasPendingApproval) // Registrar can only see departments with pending approvals
                    )
                    @if (Auth::check() && in_array('registrar', explode(',', Auth::user()->role)))
                        <h4 style="margin: 10px 0; color: var(--ckcm-color4); font-size: 1.2rem;">Registrar's Approval</h4>
                        <form action="{{ route('finalgraderegistrar.decision') }}" method="POST">
                            @csrf

                            <!-- Hidden input for departments as an array -->
                            <input type="hidden" name="department" value="{{ $department }}">

                            <!-- Example for sending the classID of the first student in the department group -->
                            <input type="hidden" name="classID" value="{{ $gradesByDepartment->first()->classID }}">

                            @foreach ($studentsByDepartment as $student)
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
                            @endforeach

                            <!-- 🔥 Registrar Approval Decision -->
                            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 10px;">
                                <style>
                                    .registrar-radio-group {
                                        display: flex;
                                        gap: 20px;
                                        align-items: center;
                                    }

                                    .registrar-radio {
                                        display: flex;
                                        align-items: center;
                                        gap: 8px;
                                        cursor: pointer;
                                        position: relative;
                                        font-weight: 500;
                                        user-select: none;
                                    }

                                    .registrar-radio input[type="radio"] {
                                        opacity: 0;
                                        position: absolute;
                                        cursor: pointer;
                                    }

                                    .registrar-radio span.radio-mark {
                                        height: 18px;
                                        width: 18px;
                                        border-radius: 50%;
                                        border: 2px solid #ccc;
                                        display: inline-block;
                                        transition: border-color 0.3s, background-color 0.3s;
                                        position: relative;
                                    }

                                    .registrar-radio input[type="radio"]:checked + span.radio-mark {
                                        border-color: #4CAF50;
                                        background-color: #4CAF50;
                                    }

                                    .registrar-radio.green input[type="radio"]:checked + span.radio-mark {
                                        border-color: var(--color-green);
                                        background-color: var(--color-green);
                                    }

                                    .registrar-radio.red input[type="radio"]:checked + span.radio-mark {
                                        border-color: var(--color-red);
                                        background-color: var(--color-red);
                                    }

                                    .registrar-radio span.radio-mark::after {
                                        content: "";
                                        position: absolute;
                                        top: 3px;
                                        left: 3px;
                                        width: 8px;
                                        height: 8px;
                                        border-radius: 50%;
                                        background: white;
                                        display: none;
                                    }

                                    .registrar-radio input[type="radio"]:checked + span.radio-mark::after {
                                        display: block;
                                    }

                                    .registrar-radio-label {
                                        color: inherit;
                                    }

                                    .registrar-radio.green .registrar-radio-label {
                                        color: var(--color-green);
                                    }

                                    .registrar-radio.red .registrar-radio-label {
                                        color: var(--color-red);
                                    }

                                    .registrar-comment-box {
                                        display: none;
                                        margin-bottom: 10px;
                                    }
                                </style>

                                <div class="registrar-radio-group">
                                    <label class="registrar-radio green">
                                        <input type="radio" name="registrar_status" value="Approved" required>
                                        <span class="radio-mark"></span>
                                        <span class="registrar-radio-label">✔️ Approved</span>
                                    </label>

                                    <label class="registrar-radio red">
                                        <input type="radio" name="registrar_status" value="Rejected" required>
                                        <span class="radio-mark"></span>
                                        <span class="registrar-radio-label">❌ Rejected</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Optional Comment for Rejection -->
                            <div class="registrar-comment-wrapper">
                                <div class="registrar-comment-box">
                                    <textarea style="background: var(--color9b); color: var(--color1); padding: 10px; width: 100%; border:1px dashed var(--color7); "
                                        name="registrar_comment" rows="3" class="form-control" placeholder="Add a comment (optional, only if rejected)..."></textarea>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    document.querySelectorAll('form').forEach(form => {
                                        const registrarRadios = form.querySelectorAll('input[name="registrar_status"]');
                                        const registrarCommentBox = form.querySelector('.registrar-comment-box');

                                        registrarRadios.forEach(radio => {
                                            radio.addEventListener('change', function () {
                                                if (this.value === 'Rejected') {
                                                    registrarCommentBox.style.display = 'block';
                                                } else {
                                                    registrarCommentBox.style.display = 'none';
                                                }
                                            });

                                            // Show comment box if "Rejected" is pre-selected on load
                                            if (radio.checked && radio.value === 'Rejected') {
                                                registrarCommentBox.style.display = 'block';
                                            }
                                        });
                                    });
                                });
                            </script>


                            <button type="submit" class="save-btn">
                                <i class="fa-solid fa-check"></i> Submit Decision
                            </button>
                        </form>
                    @endif
                @endif
                {{-- registrar approval section end --}}





            @endforeach




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


{{-- style for dropdow in searching student --}}
<style>
    .dropdown-menu {
        padding: 5px;
        gap: 5px;
        font-size: 1.1rem;
        text-align: center;

    }

    .dropdown-menu div {
        border-radius: 10px;
        padding: 5px;
        background: var(--color-blue);
        color: var(--color2);
        cursor: pointer;
        margin-bottom: 5px;
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


<!-- Full-screen Loader -->
<div id="loadingScreen"
    style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--ckcm-color1);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;">
    <div class="loader"></div>
</div>




<!-- CSS Loader Animation -->
<style>


    .loader {
        background: var(--ckcm-color1);
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>





<!-- JavaScript to Hide Loader -->
<script>
    window.onload = function() {
        setTimeout(function() {
            document.getElementById('loadingScreen').style.display = 'none';
        }, 1000);
    };
</script>
