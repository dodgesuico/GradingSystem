@extends("layouts.default")

<title>@yield("title", "CKCM Grading | View Class Details")</title>

@section("content")


<div class="dashboard">
    <h1>Class Details</h1>
    <h2>{{ $class->descriptive_title }}</h2>
    <p><strong>Subject Code:</strong> {{ $class->subject_code }}</p>
    <p><strong>Instructor:</strong> {{ $class->instructor }}</p>
    <p><strong>Academic Period:</strong> {{ $class->academic_period }}</p>
    <p><strong>Schedule:</strong> {{ $class->schedule }}</p>
    <p><strong>Status:</strong> {{ $class->status }}</p>

    <button class="btn" onclick="openAddStudentModal()">Add Student</button>
    
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
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <input type="text" id="student_id" name="student_id" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department" class="form-control" required readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddStudentModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Student</button>
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

    <div class="container">
        <h2>Class Students List</h2>
        
        @if ($classes_student->isEmpty())
            <p>No students have been added to this class yet.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
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
                            <td>{{ $classes_students->id }}</td>
                            <td>{{ $classes_students->classId }}</td>
                            <td>{{ $classes_students->studentID }}</td>
                            <td>{{ $classes_students->name }}</td>
                            <td>{{ $classes_students->email }}</td>
                            <td>{{ $classes_students->department }}</td>
                            <td>
                                <a href="" class="btn btn-sm btn-primary">Edit</a>
                                <form action="" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection

<style>
    .dashboard {
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .dashboard h1 {
        color: var(--ckcm-color4);
    }

    .dashboard h2 {
        color: var(--color6);
    }

    .dashboard p {
        font-size: 1.2rem;
        margin: 5px 0;
    }

    .dashboard a {
        color: var(--ckcm-color4);
        text-decoration: none;
        font-weight: bold;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .modal-header, .modal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .close {
        background: #f44336;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-cancel {
        background-color: #f44336;
    }

    .btn-submit {
        background-color: #4CAF50;
    }

</style>
