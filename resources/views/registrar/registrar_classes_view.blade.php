@extends('layouts.default')

<title>@yield('title', 'CKCM Grading | View Class Details')</title>

@section('content')


    <div class="dashboard">
        <div class="header-container">

            <h1>Class Details</h1>
            <h2>{{ $class->descriptive_title }}</h2>


            <div class="sub-header-container">
                <p><strong>Subject Code:</strong> {{ $class->subject_code }}</p>
                <p><strong>Instructor:</strong> {{ $class->instructor }}</p>
                <p><strong>Academic Period:</strong> {{ $class->academic_period }}</p>
                <p><strong>Schedule:</strong> {{ $class->schedule }}</p>
                <p><strong>Status:</strong> {{ $class->status }}</p>
            </div>
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
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>


        <div style="display:flex; flex-direction:row; justify-content:space-between">
            <h2 style="margin:10px 0;">Class Students List</h2>
            <button class="add-btn" onclick="openAddStudentModal()"><i class="fa-solid fa-plus"></i> Add Student</button>
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
                                <td>{{ $classes_students->classId }}</td>
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
                                    <h2 style="color:var(--color1); text-align:center;margin-bottom:1.5rem;">Remove Student
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
                                            for="confirmDelete{{ $classes_students->id }}">I am sure to delete this</label>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn-cancel"
                                            onclick="closeDeleteClassModal({{ $classes_students->id }})">Cancel</button>

                                        <form method="POST"
                                            action="{{ route('class.removestudent', ['class' => $class->id, 'student' => $classes_students->studentID, 'quizzesscores' => $classes_students->studentID]) }}">
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


        <h2 style="margin-top:20px;">Calculation</h2>

        <div class="calculation-base-container">
            @foreach (['Quizzes', 'Attendance/Behavior', 'Assignments/Participation/Project', 'Exam'] as $category)
                <div class="calculation-container">
                    <h4>{{ $category }}</h4>
                    @foreach (['Percentage (%)' => [0, 100], 'Total Score' => [0, null]] as $label => $range)
                        <div class="calculation-content">
                            <label>{{ $label }}</label>
                            <input type="number" min="{{ $range[0] }}" {{ $range[1] ? "max=$range[1]" : '' }}
                                oninput="this.value = Math.max(this.value, 0)">
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>








        <div class="grades-container">
            @foreach (['Prelim', 'Midterm', 'Semi-Finals', 'Finals'] as $period)
                <h3 style="margin-top:50px;">{{ $period }} (Raw)</h3>
                <div class="container">
                    <table class="table">
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
                            @foreach ($quizzesandscores as $quizzesandscore)
                                @php
                                    $student = $classes_student->firstWhere('studentID', $quizzesandscore->studentID);
                                @endphp
                                <tr>
                                    <td style="padding: 5px;">{{ $student ? $student->name : 'N/A' }}</td>
                                    @for ($i = 0; $i < 4; $i++)
                                        <td class="cell-content-container">
                                            <div class="content-container">
                                                <div class="cell-content"><input type="number" min="0"></div>
                                                @for ($j = 0; $j < 2; $j++)
                                                    <div class="cell-content">
                                                        <p></p>
                                                    </div>
                                                @endfor
                                            </div>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px; display:flex; width: 100%; justify-content:right;">
                    <button>Update</button>
                </div>
            @endforeach
        </div>





    </div>



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
                        <select id="student" name="name" class="form-control" onchange="fillStudentInfo()" required>
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
                    <button type="submit" class="btn-submit"><i class="fa-solid fa-file-arrow-up"></i> Add
                        Student</button>
                </div>

            </form>
        </div>
    </div>




@endsection







<style>
    table td {
        padding: 5px;

    }

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
        border: 1px solid var(--color6);
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
