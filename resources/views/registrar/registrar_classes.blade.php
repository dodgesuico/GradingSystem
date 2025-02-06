@extends("layouts.registrar")
<title>@yield("title", "CKCM Grading | Registrar Classes")</title>
@section("content")

<div class="dashboard">
    <h1>Create your Registrar classes here!</h1>

    <button class="btn" id="openModal">Add Classes</button>

        <!-- Modal -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add a New Class</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            
            <form action="{{ route('classes.create') }}" method="POST">
                @csrf
                <div>
                    <label for="subject_code">Subject Code:</label>
                    <input type="text" id="subject_code" name="subject_code" required>
                </div>
                <div>
                    <label for="descriptive_title">Descriptive Title:</label>
                    <input type="text" id="descriptive_title" name="descriptive_title" required>
                </div>
                <div class="input-group">
                    <label for="instructor">Instructor</label>
                    <select id="instructor" name="instructor">
                        <option value="" disabled selected>Select a Department</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->name }}">{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="academic_period">Academic Period:</label>
                    <input type="text" id="academic_period" name="academic_period" required>
                </div>
                <div>
                    <label for="schedule">Schedule:</label>
                    <input type="text" id="schedule" name="schedule" required>
                </div>
                <div>
                    <label for="status">Status:</label>
                    <input type="status" id="status" name="status" value="Active" readonly>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">Add Class</button>
                    <button type="button" class="close-btn" id="cancelModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('classModal');
        const openModalBtn = document.getElementById('openModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelModalBtn = document.getElementById('cancelModal');

        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>



    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject Code</th>
                <th>Descriptive Title</th>
                <th>Instructor</th>
                <th>Academic Peroiod</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($classes as $class)
                <tr>
                    <td>{{ $class->id }}</td>
                    <td>{{ $class->subject_code}}</td>
                    <td>{{ $class->descriptive_title}}</td>
                    <td>{{ $class->instructor }}</td>
                    <td>{{ $class->academic_period }}</td>
                    <td>{{ $class->schedule }}</td>
                    <td>{{ $class->status }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn" onclick="openEditClassModal({{ $class->id }})">Edit</button>
                    </td>
                </tr>

                <!-- Edit Class Modal -->
                <div id="editClassModal{{ $class->id }}" class="modal" style="display: none;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Class - {{ $class->subject_code }}</h5>
                            <button type="button" class="close" onclick="closeEditClassModal({{ $class->id }})">&times;</button>
                        </div>
                        <form action="{{ route('classes.update', $class->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="subject_code">Subject Code</label>
                                    <input type="text" class="form-control" id="subject_code" name="subject_code" value="{{ $class->subject_code }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="descriptive_title">Descriptive Title</label>
                                    <input type="text" class="form-control" id="descriptive_title" name="descriptive_title" value="{{ $class->descriptive_title }}" required>
                                </div>
                                <div class="input-group">
                                    <label for="instructor">Instructor</label>
                                    <select id="instructor" name="instructor">
                                        <option value="" disabled selected>Select a Department</option>
                                        @foreach ($instructors as $instructor)
                                            <option value="{{ $instructor->name }}">{{ $instructor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="academic_period">Academic Period</label>
                                    <input type="text" class="form-control" id="academic_period" name="academic_period" value="{{ $class->academic_period }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="schedule">Schedule</label>
                                    <input type="text" class="form-control" id="schedule" name="schedule" value="{{ $class->schedule }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Active" {{ $class->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ $class->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeEditClassModal({{ $class->id }})">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

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

            @endforeach
        </tbody>
    </table>
</div>



@endsection


<style>
    .dashboard {
        margin-top: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 10px;
        
    }

    .dashboard h1 {
        color: var(--ckcm-color4)
    }

    .dashboard h2 {
        color: var(--color6);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    thead th {
        background-color: #f4f4f4;
        text-align: left;
        padding: 10px;
    }

    tbody td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    .empty-message {
        text-align: center;
        color: red;
        padding: 10px;
    }

    /* Modal Background */
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
    
    /* Modal Content */
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
    
    .modal-footer {
        text-align: right;
    }

    .close-btn .close {
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
</style>