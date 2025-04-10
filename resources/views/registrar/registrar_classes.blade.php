@extends('layouts.default')

@section('content')

    <div class="dashboard">

        <div class="header-container">

            <h1>Classes</h1>
        </div>

        <style>
            .header-container {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
        </style>

        <div class="classes-header">
            <input type="text" id="searchInput" class="search" placeholder="🔎 Quick Search...">

        </div>



        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll("table tbody tr");

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                });
            });
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

        <div style="display:flex; flex-direction:row; justify-content:space-between;width:100%;">
            <h2 style="margin:10px 0; ">Class List</h2>
            @if (Auth::check() && (in_array('dean', explode(',', Auth::user()->role))))
                <button class="add-btn" id="openModal"><i class="fa-solid fa-plus"></i> Add Classes</button>
            @endif
        </div>


        @if (Auth::check() && (in_array('dean', explode(',', Auth::user()->role)) || in_array('registrar', explode(',', Auth::user()->role))))
            @php
                $userDepartment = Auth::user()->department;
                $userName = Auth::user()->name; // Get logged-in user's name

                // Filter classes based on conditions
                $filteredClasses = $classes->filter(function ($class) use ($userDepartment, $userName, $classes_student, $finalGrades) {
                    // Check if students exist and match the department
                    $hasStudentsInDepartment = isset($classes_student[$class->id]) && $classes_student[$class->id]->contains('department', $userDepartment);

                    // Check if the class was added by the logged-in user
                    $isAddedByUser = $class->added_by === $userName;

                    // Check if any final grade record for this class has 'Pending' status
                    $isRegistrarAllowed = in_array('registrar', explode(',', Auth::user()->role))
                        && $finalGrades->where('classID', $class->id)->contains('registrar_status', 'Pending');

                    // Show if either condition is met
                    return $hasStudentsInDepartment || $isAddedByUser || $isRegistrarAllowed;
                });
            @endphp

            @if ($filteredClasses->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject Code</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th>Instructor</th>
                            <th>Academic Period</th>
                            <th>Academic Year</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filteredClasses as $class)
                            <tr>
                                <td>{{ $class->id }}</td>
                                <td>{{ $class->subject_code }}</td>
                                <td>{{ $class->descriptive_title }}</td>
                                <td>{{ $class->units }}</td>
                                <td>{{ $class->instructor }}</td>
                                <td>{{ $class->academic_period }}</td>
                                <td>{{ $class->academic_year }}</td>
                                <td>{{ $class->schedule }}</td>
                                <td class="status {{ strtolower($class->status) }}">{{ $class->status }}</td>
                                <td style="text-align: center">
                                    <a href="{{ route('class.show', $class->id) }}" class="view-btn"><i class="fa-solid fa-up-right-from-square"></i> View Class</a>

                                    @php
                                        $isOwner = auth()->user()->name === $class->added_by;
                                    @endphp

                                    @if ($isOwner)
                                        |
                                        <button class="edit-btn" onclick="openEditClassModal({{ $class->id }})">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button> |
                                        <button class="delete-btn" onclick="openDeleteClassModal({{ $class->id }})">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @include('registrar.registrar_classes_edit_and_delete')
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif








    </div>


    @include('registrar.registrar_classes_add')


@endsection







{{-- script for unlock actions --}}
  <!-- Password Modal -->
  <div id="passwordModal" class="modal">
    <div class="modal-content" style="display: flex; flex-direction: column; gap: 10px;">
        <h3 style="color: var(--color1)">Enter Class Password</h3>
        <input type="password" id="classPassword" placeholder="Enter Password">
        <button id="confirmPasswordBtn" class="save-btn">Submit</button>
        <p id="passwordError" style="color:var(--color-red); display: none; text-align: center;">Incorrect password. Try again.</p>
    </div>
</div>
<script>
    let selectedClassId = null;

    function openPasswordModal(classId) {
        selectedClassId = classId;
        document.getElementById("passwordModal").style.display = "block";
    }

    document.getElementById("confirmPasswordBtn").addEventListener("click", function() {
        let password = document.getElementById("classPassword").value;

        fetch("{{ route('class.verifyPassword') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id: selectedClassId,
                password: password
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`actions-${selectedClassId}`).innerHTML = `
                    <a href="{{ route('class.show', '') }}${selectedClassId}" class="view-btn">
                        <i class="fa-solid fa-up-right-from-square"></i> View Class
                    </a> |
                    <button class="edit-btn" onclick="openEditClassModal(${selectedClassId})">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button> |
                    <button class="delete-btn" onclick="openDeleteClassModal(${selectedClassId})">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                `;
                closePasswordModal();
            } else {
                document.getElementById("passwordError").style.display = "block";
            }
        })
        .catch(error => console.error("Error:", error));
    });

    function closePasswordModal() {
        document.getElementById("passwordModal").style.display = "none";
        document.getElementById("classPassword").value = "";
        document.getElementById("passwordError").style.display = "none";
    }

    document.querySelector(".close").addEventListener("click", closePasswordModal);
</script>
{{-- end for action script --}}

























{{-- former code for td --}}

{{-- <td  style="text-align:center; background-color: var(--color9b);">
<!-- Edit Button -->
<a href="{{ route('class.show', $class->id) }}" class="view-btn"><i class="fa-solid fa-up-right-from-square"></i> View Class</a> |
<button class="edit-btn" onclick="openEditClassModal({{ $class->id }})"><i class="fa-solid fa-pen-to-square"></i> Edit</button> |
<button class="delete-btn" onclick="openDeleteClassModal({{ $class->id }})"><i class="fa-solid fa-trash"></i> Delete</button>
</td> --}}





<style>
    .classes-header {
        display: flex;
        margin-top: 10px;
        align-items: center;
        justify-content: space-between;
        width: 100%;
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
