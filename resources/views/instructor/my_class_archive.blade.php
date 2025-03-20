@extends('layouts.default')

@section('content')

    <div class="dashboard">
        <div class="header-container">
            <h1>My Class Archive</h1>
        </div>

        <style>
              .header-container {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
        </style>

        <div class="classes-header" style="margin-bottom: 10px; ">
            <input type="text" id="searchInput" class="search" placeholder="🔎 Quick Search...">
        </div>



        <div class="class-archive-container">
            @foreach($archivedData as $academic_year => $classes)
                <h2 style="color: var(--ckcm-color4)">Academic Year: {{ $academic_year }}</h2>
                @foreach($classes as $classID => $terms)
                    <h3 style="color:var(--color5); margin: 5px 0;">Class ID: {{ $classID }}</h3>
                    @foreach($terms as $periodic_term => $records)
                        <h4 style="color:var(--color5); margin: 5px 0;">Term: {{ ucfirst($periodic_term) }}</h4>
                        <table >
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
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
                                    {{-- <th>Created At</th>
                                    <th>Updated At</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        {{-- <td>{{ $record->id }}</td> --}}
                                        <td style="background: var(--color9b);">{{ $record->studentID }}</td>
                                        <td >{{ $record->quiz_percentage }}%</td>
                                        <td style="background: var(--color9b);">{{ $record->quiz_total_score }}</td>
                                        <td style="background: var(--color9b); color: var(--color-green);">{{ $record->quizzez }}</td>
                                        <td>{{ $record->attendance_percentage }}%</td>
                                        <td style="background: var(--color9b);">{{ $record->attendance_total_score }}</td>
                                        <td style="background: var(--color9b); color: var(--color-green);">{{ $record->attendance_behavior }}</td>
                                        <td>{{ $record->assignment_percentage }}%</td>
                                        <td style="background: var(--color9b);">{{ $record->assignment_total_score }}</td>
                                        <td style="background: var(--color9b); color: var(--color-green);">{{ $record->assignments }}</td>
                                        <td>{{ $record->exam_percentage }}%</td>
                                        <td style="background: var(--color9b);">{{ $record->exam_total_score }}</td>
                                        <td style="background: var(--color9b); color: var(--color-green);">{{ $record->exam }}</td>
                                        {{-- <td>{{ $record->created_at }}</td>
                                        <td>{{ $record->updated_at }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

    .class-archive-container{
        width: 100%;
    }

    .class-archive-container table th{
        font-size: 1rem;
    }

    .class-archive-container table td{
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
