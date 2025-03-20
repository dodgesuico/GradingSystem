@extends('layouts.default')

@section('content')
    <div class="dashboard">
        <div class="header-container">
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

                <select name="instructor">
                    <option value="">Select Instructor</option>
                    @foreach ($archivedData as $periodGroups)
                        @foreach ($periodGroups as $instructorGroups)
                            @foreach ($instructorGroups as $instructor => $data)
                                <option value="{{ $instructor }}" {{ request('instructor') == $instructor ? 'selected' : '' }}>
                                    {{ $instructor }}
                                </option>
                            @endforeach
                        @endforeach
                    @endforeach
                </select>

                <input type="text" name="subject_code" placeholder="Search Subject Code" value="{{ request('subject_code') }}">

                <button type="submit">Filter</button>
            </form>
        </div>

        <style>

            .header-container{
                display: flex;
                flex-direction: column;
                justify-content: start;
                width: 100%;
                gap: 10px;
            }
            .filter-form {
                display: flex;
                gap: 10px;
                margin-bottom: 15px;
            }
            .filter-form select, .filter-form input {
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
                <h2 style="color: var(--ckcm-color4); margin-bottom: 10px;">Academic Year: {{ $academic_year }}</h2>
                @foreach ($periodGroups as $academic_period => $instructorGroups)
                    <h3 style="color: var(--color5); margin: 5px 0;">Academic Period: {{ $academic_period }}</h3>
                    @foreach ($instructorGroups as $instructor => $titleGroups)
                        <h4 style="color: var(--color5); margin: 5px 0; font-size: 1rem;">Instructor: {{ $instructor }}</h4>
                        @foreach ($titleGroups as $descriptive_title => $subjectGroups)
                            <h5 style="color: var(--color5); margin: 5px 0; font-size: 1rem;">Course: {{ $descriptive_title }}</h5>
                            @foreach ($subjectGroups as $subject_code => $termGroups)
                                <h6 style="color: var(--color5); margin: 5px 0; font-size: 1rem;">Subject Code: {{ $subject_code }}</h6>
                                @foreach ($termGroups as $periodic_term => $records)
                                    <h6 style="color: var(--color5); margin: 5px 0; font-size: 1rem;">Term: {{ ucfirst($periodic_term) }}
                                    </h6>
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
                                                    <td style="color: var(--color-green); border: 1px solid var(--color7);">
                                                        {{ $record->quizzez }}</td>
                                                    <td>{{ $record->attendance_percentage }}%</td>
                                                    <td>{{ $record->attendance_total_score }}</td>
                                                    <td
                                                        style=" color: var(--color-green); border: 1px solid var(--color7);">
                                                        {{ $record->attendance_behavior }}</td>
                                                    <td>{{ $record->assignment_percentage }}%</td>
                                                    <td>{{ $record->assignment_total_score }}</td>
                                                    <td
                                                        style=" color: var(--color-green); border: 1px solid var(--color7);">
                                                        {{ $record->assignments }}</td>
                                                    <td>{{ $record->exam_percentage }}%</td>
                                                    <td style="">{{ $record->exam_total_score }}</td>
                                                    <td
                                                        style=" color: var(--color-green); border: 1px solid var(--color7);">
                                                        {{ $record->exam }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>
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
