@extends("layouts.default")

<title>@yield("title", "CKCM Grading | View Class Details")</title>

@section("content")


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

    <div class="search-container">
        <input type="text" class="search" name="" id="" placeholder="🔎 Quick Search...">

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
        @if (session()->has("success"))
        <div class="alert alert-success">
            {{ session()->get("success") }}
        </div>
        @endif

        @if (session()->has("error"))
        <div class="alert alert-danger">
            {{ session()->get("error") }}
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
                        <a href="" class="view-btn"><i class="fa-solid fa-up-right-from-square"></i> View Student </a> |
                        <button href="" class="edit-btn"><i class="fa-solid fa-pen-to-square"></i> Edit</button> |
                        <form action="" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fa-solid fa-trash"></i>Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>














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
                        <option value="{{ $student->id }}"
                            data-name="{{ $student->name }}"
                            data-email="{{ $student->email }}"
                            data-department="{{ $student->department }}"
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
                <button type="submit" class="btn-submit"><i class="fa-solid fa-file-arrow-up"></i> Add Student</button>
            </div>

        </form>
    </div>
</div>
@endsection






























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
</style>