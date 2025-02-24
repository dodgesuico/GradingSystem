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
                        <th>Instructor</th>
                        <th>Academic Period</th>
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
                                <td>${grade.id}</td>
                                  <td>${grade.name}</td>
                                   <td>${grade.email}</td>
                                <td>${grade.department}</td>
                                <td>${grade.classID}</td>
                                <td>${grade.subject_code}</td>
                                <td>${grade.descriptive_title}</td>
                                <td>${grade.instructor}</td>
                                <td>${grade.academic_period}</td>
                                <td>${grade.schedule}</td>
                                <td>${grade.studentID}</td>
                                <td>${grade.prelim ?? 'N/A'}</td>
                                <td>${grade.midterm ?? 'N/A'}</td>
                                <td>${grade.semi_finals ?? 'N/A'}</td>
                                <td>${grade.final ?? 'N/A'}</td>
                                <td>${grade.remarks ?? 'N/A'}</td>


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
