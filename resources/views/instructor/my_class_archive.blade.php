@extends('layouts.default')

@section('content')
    <div class="dashboard">
        <div class="header-container" style="display: flex; flex-direction: column; width: 100%; gap: 10px;">
            <h1>My Class Archive</h1>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('instructor.my_class_archive') }}" class="filter-form">
                <select name="academic_year">
                    <option value="">Select Academic Year</option>
                    @foreach ($archivedData as $year => $data)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="subject_code" placeholder="Search Subject Code"
                    value="{{ request('subject_code') }}">

                <button type="submit">Filter</button>
            </form>
        </div>

        <style>
            .folder {
                background: var(--ckcm-color2);
                color: white;
                padding: 10px;
                margin: 5px 0;
                cursor: pointer;
                font-weight: bold;
                border-radius: 5px;
            }

            .folder-content {

                display: none;
                padding-left: 20px;
                margin-top: 5px;
            }

            .table-container {
                padding-left: 20px;
                margin-top: 5px;
            }

            .filter-form {
                display: flex;
                gap: 10px;
                margin-bottom: 15px;
            }

            .filter-form select,
            .filter-form input {
                padding: 5px;
            }

            .filter-form button {
                background: var(--ckcm-color4);
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
            }
        </style>

        <div class="class-archive-container">
            @foreach ($archivedData as $academic_year => $periodGroups)
                <div class="folder" onclick="toggleFolder('year-{{ $academic_year }}')">
                    📁 Academic Year: {{ $academic_year }}
                </div>
                <div class="folder-content" id="year-{{ $academic_year }}">
                    @foreach ($periodGroups as $academic_period => $subjectGroups)
                        <div class="folder" style="background:var(--color7)"
                            onclick="toggleFolder('period-{{ $academic_year }}-{{ $academic_period }}')">
                            📂 Academic Period: {{ $academic_period }}
                        </div>
                        <div class="folder-content" id="period-{{ $academic_year }}-{{ $academic_period }}">
                            @foreach ($subjectGroups as $subject_code => $instructorGroups)
                                <div class="folder" style="background:var(--color8)"
                                    onclick="toggleFolder('subject-{{ $academic_year }}-{{ $academic_period }}-{{ $subject_code }}')">
                                    📂 Subject Code: {{ $subject_code }}
                                </div>
                                <div class="folder-content" style=" background: var(--color9b); padding:10px 20px;"
                                    id="subject-{{ $academic_year }}-{{ $academic_period }}-{{ $subject_code }}">
                                    @foreach ($instructorGroups as $instructor => $titleGroups)
                                        <h4 style="color: var(--color1); font-size: 1rem;">Instructor: {{ $instructor }}
                                        </h4>
                                        @foreach ($titleGroups as $descriptive_title => $termGroups)
                                            <h5 style="color: var(--color1); font-size: 1rem;">Descriptive Title:
                                                {{ $descriptive_title }}</h5>




                                            {{-- for tabbing in class score archive --}}
                                            <style>
                                                .tab-buttons {
                                                    display: flex;
                                                    gap: 10px;
                                                    margin: 10px 0 0 0;
                                                    flex-wrap: wrap;
                                                }

                                                .tab-buttons button {
                                                    padding: 5px 10px;
                                                    background-color: var(--color9b);
                                                    color: white;
                                                    border: none;
                                                    cursor: pointer;
                                                    font-weight: bold;
                                                    transition: background 0.3s ease;
                                                }

                                                .tab-buttons button.active {
                                                    background-color: var(--color8);
                                                    border: 1px solid var(--ckcm-color4);
                                                    color: var(--ckcm-color4);

                                                }

                                                .tab-content {
                                                    display: none;
                                                }

                                                .tab-content.active {
                                                    display: block;
                                                }

                                                .table-container {
                                                    overflow-x: auto;
                                                    margin-bottom: 2rem;
                                                }

                                                table {
                                                    width: 100%;
                                                    border-collapse: collapse;
                                                }

                                                th,
                                                td {
                                                    padding: 8px;
                                                    border: 1px solid var(--color7);
                                                    text-align: center;
                                                }
                                            </style>

                                            <div class="tabbed-wrapper">
                                                <div class="tab-buttons">
                                                    @foreach ($termGroups as $term => $group)
                                                        <button class="{{ $loop->first ? 'active' : '' }}"
                                                            data-term="{{ $term }}" onclick="showTab(this)">
                                                            {{ ucfirst($term) }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                @foreach ($termGroups as $term => $records)
                                                    <div class="tab-content {{ $loop->first ? 'active' : '' }}"
                                                        data-term="{{ $term }}">
                                                        <h6 style="color: var(--color1); padding: 10px 0; font-size: 1.2rem;">
                                                            Term: {{ ucfirst($term) }}
                                                        </h6>
                                                        <div class="table-container">
                                                            <table>
                                                                <thead>
                                                                    <tr>
                                                                        <th>Student ID</th>
                                                                        <th>Quiz (%)</th>
                                                                        <th>Quiz Score</th>
                                                                        <th>Quizzes</th>
                                                                        <th>Attendance (%)</th>
                                                                        <th>Attendance Total Score</th>
                                                                        <th>Attendance Score</th>
                                                                        <th>Assignments (%)</th>
                                                                        <th>Assignment Total Score</th>
                                                                        <th>Assignments Score</th>
                                                                        <th>Exam (%)</th>
                                                                        <th>Exam Total Score</th>
                                                                        <th>Exam Score</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($records as $record)
                                                                        <tr>
                                                                            <td>{{ $record->studentID }}</td>
                                                                            <td>{{ $record->quiz_percentage }}%</td>
                                                                            <td>{{ $record->quiz_total_score }}</td>
                                                                            <td
                                                                                style="color: var(--color-green); border: 1px solid var(--color7);">
                                                                                {{ $record->quizzez }}</td>
                                                                            <td>{{ $record->attendance_percentage }}%</td>
                                                                            <td>{{ $record->attendance_total_score }}</td>
                                                                            <td
                                                                                style="color: var(--color-green); border: 1px solid var(--color7);">
                                                                                {{ $record->attendance_behavior }}</td>
                                                                            <td>{{ $record->assignment_percentage }}%</td>
                                                                            <td>{{ $record->assignment_total_score }}</td>
                                                                            <td
                                                                                style="color: var(--color-green); border: 1px solid var(--color7);">
                                                                                {{ $record->assignments }}</td>
                                                                            <td>{{ $record->exam_percentage }}</td>
                                                                            <td>{{ $record->exam_total_score }}</td>
                                                                            <td
                                                                                style="color: var(--color-green); border: 1px solid var(--color7);">
                                                                                {{ $record->exam }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <script>
                                                function showTab(button) {
                                                    const term = button.getAttribute('data-term');
                                                    const wrapper = button.closest('.tabbed-wrapper');

                                                    const buttons = wrapper.querySelectorAll('.tab-buttons button');
                                                    const contents = wrapper.querySelectorAll('.tab-content');

                                                    buttons.forEach(btn => btn.classList.remove('active'));
                                                    contents.forEach(content => content.classList.remove('active'));

                                                    button.classList.add('active');
                                                    wrapper.querySelector(`.tab-content[data-term="${term}"]`).classList.add('active');
                                                }
                                            </script>

                                            {{-- end for tabbing in class score archive --}}






                                            {{-- Final Grades Table --}}
                                            <div class="table-container" style="margin-top: 20px;">
                                                <h6 style="color: var(--color1); font-size: 1.2rem; margin-bottom: 10px;">
                                                    Final Grades</h6>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Student ID</th>
                                                            <th>Prelim</th>
                                                            <th>Midterm</th>
                                                            <th>Semi-Finals</th>
                                                            <th>Final</th>
                                                            <th>Remarks</th>
                                                            {{-- <th>Status</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($records as $record)
                                                            @php
                                                                // Rebuild the key used for matching archived final grades
                                                                $finalKey =
                                                                    $academic_year .
                                                                    '|' .
                                                                    $academic_period .
                                                                    '|' .
                                                                    $subject_code .
                                                                    '|' .
                                                                    $instructor .
                                                                    '|' .
                                                                    $descriptive_title .
                                                                    '|' .
                                                                    $record->studentID;
                                                                $finalGrade = $finalGrades[$finalKey][0] ?? null;
                                                            @endphp
                                                            @if ($finalGrade)
                                                                <tr>
                                                                    <td>{{ $record->studentID }}</td>
                                                                    <td>{{ $finalGrade->prelim }}</td>
                                                                    <td>{{ $finalGrade->midterm }}</td>
                                                                    <td>{{ $finalGrade->semi_finals }}</td>
                                                                    <td>{{ $finalGrade->final }}</td>
                                                                    <td>{{ $finalGrade->remarks }}</td>
                                                                    {{-- <td>{{ $finalGrade->status }}</td> --}}
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleFolder(id) {
            var content = document.getElementById(id);
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>
@endsection



<style>
    .classes-header {
        display: flex;
        margin-top: 10px;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .class-archive-container {
        width: 100%;
    }

    .class-archive-container table th {
        font-size: 1rem;
    }

    .class-archive-container table td {
        border-left: 0;
        border-right: 0;
        text-align: center;
    }

    .empty-message {
        text-align: center;
        color: red;
        padding: 10px;
    }

    .add-btn {
        background: transparent;
        border: 0;
    }

    .add-btn:hover {
        background: transparent;

    }

    .dashboard {
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;


    }

    .dashboard h1 {
        color: var(--ckcm-color4)
    }

    .dashboard h2 {
        color: var(--color5);
    }
</style>
