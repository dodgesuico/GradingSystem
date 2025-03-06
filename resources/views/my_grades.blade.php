@extends('layouts.default')
@section('content')

    <div class="dashboard">
        <div class="header-container">
            <h1>My Grades</h1>
            <button id="toggleViewBtn"><i class="fa-solid fa-chart-bar"></i> View Grades in Graph</button>
            <canvas id="gradesChart" style="max-width: 600px; display: none; overflow: auto;"></canvas>
            <style>
                .header-container {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    margin-bottom: 10px;
                }


                #toggleViewBtn {
                    padding: 5px;
                    cursor: pointer;
                    margin-top: 10px;
                    border-radius: 5px;

                }
            </style>
        </div>

        <div id="gradesTable" class="grades-container">
            @if ($grades->isEmpty())
                <p>No grades available.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Descriptive Title</th>
                            <th>Instructor</th>
                            <th>Prelim</th>
                            <th>Midterm</th>
                            <th>Semi Finals</th>
                            <th>Finals</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                            <tr>
                                <td>{{ $grade->subject_code }}</td>
                                <td style="max-width:50px; overflow: auto;">{{ $grade->descriptive_title }}</td>
                                <td>{{ $grade->instructor }}</td>
                                <td
                                    style="color: {{ $grade->prelim <= 3.0 ? 'green' : 'red' }}; background-color:var(--color9b);">
                                    {{ $grade->prelim }}
                                </td>
                                <td
                                    style="color: {{ $grade->midterm <= 3.0 ? 'green' : 'red' }}; background-color:var(--color9b);">
                                    {{ $grade->midterm }}
                                </td>
                                <td
                                    style="color: {{ $grade->semi_finals <= 3.0 ? 'green' : 'red' }}; background-color:var(--color9b);">
                                    {{ $grade->semi_finals }}
                                </td>
                                <td
                                    style="color: {{ $grade->final <= 3.0 ? 'green' : 'red' }}; background-color:var(--color9b);">
                                    {{ $grade->final }}
                                </td>
                                <td
                                    style="color: {{ strtolower($grade->remarks) == 'failed' ? 'red' : 'green' }}; background-color:var(--color9b);">
                                    {{ $grade->remarks }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @endif
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chartInstance = null;
        document.getElementById('toggleViewBtn').addEventListener('click', function() {
            const tableView = document.getElementById('gradesTable');
            const chartCanvas = document.getElementById('gradesChart');
            const btn = document.getElementById('toggleViewBtn');

            if (tableView.style.display === "none") {
                tableView.style.display = "block";
                chartCanvas.style.display = "none";
                btn.innerHTML = '<i class="fas fa-chart-bar"></i> View Grades in Graph'; // Graph icon
            } else {
                tableView.style.display = "none";
                chartCanvas.style.display = "block";
                btn.innerHTML = '<i class="fas fa-table"></i> View Grades in Table'; // Table icon

                if (!chartInstance) {
                    const ctx = chartCanvas.getContext('2d');
                    const subjects = @json($grades->pluck('subject_code'));
                    const prelimGrades = @json($grades->pluck('prelim'));
                    const midtermGrades = @json($grades->pluck('midterm'));
                    const semiFinalGrades = @json($grades->pluck('semi_finals'));
                    const finalGrades = @json($grades->pluck('final'));

                    const colors = ['#007f5f', '#2b9348', '#55a630', '#80b918'];
                    chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: subjects,
                            datasets: [{
                                    label: 'Prelim',
                                    data: prelimGrades.map(g => 5 - g),
                                    backgroundColor: colors[0]
                                },
                                {
                                    label: 'Midterm',
                                    data: midtermGrades.map(g => 5 - g),
                                    backgroundColor: colors[1]
                                },
                                {
                                    label: 'Semi Finals',
                                    data: semiFinalGrades.map(g => 5 - g),
                                    backgroundColor: colors[2]
                                },
                                {
                                    label: 'Finals',
                                    data: finalGrades.map(g => 5 - g),
                                    backgroundColor: colors[3]
                                }
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    min: 0, // Ensures bars grow from bottom
                                    max: 4, // Max height (since 5-1 = 4)
                                    ticks: {
                                        stepSize: 0.25,
                                        callback: function(value) {
                                            return 5 - value; // Displays grades correctly

                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>


@endsection






<style>
    .dashboard {
        padding: 10px;
        overflow: auto;
    }



    .dashboard h1 {
        color: var(--ckcm-color4);

    }


    @media (max-width: 480px){

        .header-container h1{
            font-size: 1.6rem;
        }
        table th:nth-child(2),
        table td:nth-child(2),
        table th:nth-child(3),
        table td:nth-child(3){
            display: none;
        }

        .grades-container table td,
        .grades-container table th {
            font-size: 1rem;
            padding: 5px;
        }
    }

    @media (max-width: 768px) {

        .grades-container table td,
        .grades-container table th {

            padding: 5px;
        }
    }
</style>
