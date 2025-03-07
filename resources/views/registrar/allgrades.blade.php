@extends('layouts.default')

<title>@yield('title', 'CKCM Grading | All Grades')</title>

@section('content')
    <div class="dashboard">
        <h1 style="margin-bottom: 10px">All Grades</h1>

        <!-- Search and Filter -->
        <input type="text" id="search" class="search" placeholder="🔎 Search Student" class="form-control"
            style="margin: 0 10px 10px 0">

        <select id="departmentFilter" class="form-control">
            <option value="all">All Departments</option>
            @foreach ($departments as $department)
                <option value="{{ $department }}">{{ $department }}</option>
            @endforeach
        </select>

        <div class="all-grades-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Class ID</th>
                        <th>Subject Code</th>
                        <th>Descriptive Title</th>
                        <th>Units</th>
                        <th>Instructor</th>
                        <th>Academic Period</th>
                        <th>Academic Year</th>
                        <th>Schedule</th>
                        <th>Student ID</th>
                        <th>Prelim</th>
                        <th>Midterm</th>
                        <th>Semi Finals</th>
                        <th>Final</th>
                        <th>Remarks</th>
                        {{-- <th>Status</th> --}}
                    </tr>
                </thead>
                <tbody id="gradesTable">
                    <!-- Grades will be dynamically displayed here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function fetchGrades() {
                let search = document.getElementById("search").value;
                let department = document.getElementById("departmentFilter").value;

                fetch(`/api/grades?search=${search}&department=${department}`)
                    .then(response => response.json())
                    .then(data => {
                        let gradesTable = document.getElementById("gradesTable");
                        gradesTable.innerHTML = "";

                        if (data.length === 0) {
                            gradesTable.innerHTML = "<tr><td colspan='18'>No Student found.</td></tr>";
                            return;
                        }

                        data.forEach(grade => {
                            gradesTable.innerHTML += `
                            <tr>
                                <td >${grade.id}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.name}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.email}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.department}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.classID}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.subject_code}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.descriptive_title}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.units}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.instructor}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.academic_period}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.academic_year}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.schedule}</td>
                                <td style="border: 0; border-bottom: 1px solid var(--color7);">${grade.studentID}</td>
                                <td  style="background:var(--color9b)">${grade.prelim ?? 'N/A'}</td>
                                <td  style="background:var(--color9b)">${grade.midterm ?? 'N/A'}</td>
                                <td  style="background:var(--color9b)">${grade.semi_finals ?? 'N/A'}</td>
                                <td  style="background:var(--color9b)">${grade.final ?? 'N/A'}</td>
                                <td  style="background:var(--color9b)">${grade.remarks ?? 'N/A'}</td>


                            </tr>
                        `;
                        });
                    })
                    .catch(error => console.error("Error fetching grades:", error));
            }

            document.getElementById("search").addEventListener("input", fetchGrades);
            document.getElementById("departmentFilter").addEventListener("change", fetchGrades);

            fetchGrades(); // Load data when the page loads
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

</style>
