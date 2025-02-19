@extends('layouts.default')

<title>@yield('title', 'CKCM Grading | View Users')</title>

@section('content')
    <div class="dashboard">
        <h1>Users List</h1>

        <!-- Search and Filter Form -->
        <form id="filterForm">
            <input type="text" name="search" id="search" class="search" placeholder="🔎 Search by name or email"
                value="{{ request('search') }}">

            <select name="department" id="department">
                <option value="">Filter by Department</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>

            <select name="role" id="role">
                <option value="">Filter by Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>

            <button type="button" id="resetFilters">Reset</button>
        </form>


        <div class="message-container">
            @if (session('error'))
                <div class="alert alert-danger">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong> Please check the following issues:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif
        </div>


        <!-- Users Table -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->department }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <button class="edit-user-btn" data-user-id="{{ $user->id }}">Edit User</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit User</h2>

            <!-- 🟢 Form for submitting user edits -->
            <form action="{{ route('user.edituser') }}" method="POST">
                @csrf
                <input type="hidden" id="modalUserId" name="user_id">

                <!-- Name -->
                <label for="editUserName">Name:</label>
                <input type="text" id="editUserName" name="name">

                <!-- Email -->
                <label for="editUserEmail">Email:</label>
                <input type="email" id="editUserEmail" name="email">

                <!-- Department -->
                <label for="editUserDepartment">Department:</label>
                <select id="editUserDepartment" name="department">
                    <option value="">Select Department</option>
                    @foreach ($department as $departments)
                        <option value="{{ $departments }}">{{ $departments }}</option>
                    @endforeach
                </select>

                <!-- Roles Section -->
                <label>Roles:</label>
                <div id="roleContainer" class="role-container">
                    <!-- Selected roles will appear here -->
                </div>

                <!-- Role Dropdown -->
                <div class="dropdown-menu" id="roleDropdown">
                    <div class="dropdown-item" data-role="student">Student</div>
                    <div class="dropdown-item" data-role="instructor">Instructor</div>
                    <div class="dropdown-item" data-role="dean">Dean</div>
                    <div class="dropdown-item" data-role="registrar">Registrar</div>
                    <div class="dropdown-item" data-role="admin">Admin</div>
                </div>

                <!-- Hidden input to store selected roles -->
                <input type="hidden" id="rolesInput" name="roles">

                <!-- Submit Button -->
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>






    <script>
        $(document).ready(function() {
            $(".edit-user-btn").click(function() {
                let userId = $(this).data("user-id");
                let userName = $(this).closest("tr").find("td:nth-child(2)").text().trim();
                let userEmail = $(this).closest("tr").find("td:nth-child(3)").text().trim();
                let userDepartment = $(this).closest("tr").find("td:nth-child(4)").text().trim();
                let userRoles = $(this).closest("tr").find("td:nth-child(5)").text().trim().split(
                /\s*,\s*/);

                $("#modalUserId").val(userId);
                $("#editUserName").val(userName);
                $("#editUserEmail").val(userEmail);
                $("#editUserDepartment").val(userDepartment);

                let rolesContainer = $("#roleContainer");
                rolesContainer.empty();

                userRoles.forEach(role => {
                    if (role.trim() !== "") {
                        rolesContainer.append(createRoleBadge(role));
                    }
                });

                updateRolesInput(); // 🟢 Ensure hidden input updates
                updateDropdown(); // 🟢 Update dropdown to hide selected roles

                $("#editUserModal").fadeIn();
            });

            $(".close").click(function() {
                $("#editUserModal").fadeOut();
            });

            $(window).click(function(event) {
                if ($(event.target).is("#editUserModal")) {
                    $("#editUserModal").fadeOut();
                }
            });

            // Show dropdown when clicking the role container (not when clicking 'X')
            $(document).on("click", "#roleContainer", function(event) {
                if (!$(event.target).hasClass("remove-role")) {
                    $("#roleDropdown").toggle();
                }
            });

            // Add role when selecting from dropdown
            $(document).on("click", ".dropdown-item", function() {
                let selectedRole = $(this).data("role");
                if (selectedRole && !roleExists(selectedRole)) {
                    $("#roleContainer").append(createRoleBadge(selectedRole));
                    updateRolesInput(); // 🟢 Update hidden input
                    updateDropdown(); // 🟢 Hide selected role from dropdown
                }
                $("#roleDropdown").hide();
            });

            // Remove role on clicking 'X' and restore in dropdown
            $(document).on("click", ".remove-role", function(event) {
                event.stopPropagation();
                $(this).parent().remove();
                updateRolesInput(); // 🟢 Update hidden input
                updateDropdown(); // 🟢 Show role back in dropdown
            });

            function roleExists(role) {
                let exists = false;
                $("#roleContainer .role-badge").each(function() {
                    if ($(this).data("role") === role) {
                        exists = true;
                    }
                });
                return exists;
            }

            function createRoleBadge(role) {
                return `
            <span class="role-badge" data-role="${role}">
                ${role.charAt(0).toUpperCase() + role.slice(1)}
                <button class="remove-role">X</button>
            </span>`;
            }

            function updateRolesInput() {
                let selectedRoles = [];
                $("#roleContainer .role-badge").each(function() {
                    selectedRoles.push($(this).data("role"));
                });

                // 🟢 Store the roles as a JSON string in the hidden input
                $("#rolesInput").val(JSON.stringify(selectedRoles));
            }

            function updateDropdown() {
                let selectedRoles = [];
                $("#roleContainer .role-badge").each(function() {
                    selectedRoles.push($(this).data("role"));
                });

                $("#roleDropdown .dropdown-item").each(function() {
                    let role = $(this).data("role");
                    if (selectedRoles.includes(role)) {
                        $(this).hide(); // ✅ Hide selected roles
                    } else {
                        $(this).show(); // ✅ Show unselected roles
                    }
                });
            }
        });
    </script>


    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchUsers() {
                let search = $('#search').val();
                let department = $('#department').val();
                let role = $('#role').val();

                $.ajax({
                    url: "{{ route('user.show') }}",
                    type: "GET",
                    data: {
                        search: search,
                        department: department,
                        role: role
                    },
                    success: function(response) {
                        let users = response.users;
                        let tableBody = '';

                        if (users.length > 0) {
                            users.forEach(user => {
                                tableBody += `
                                <tr>
                                    <td>${user.id}</td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>${user.department}</td>
                                    <td>${user.role}</td>
                                    <td>${new Date(user.created_at).toISOString().split('T')[0]}</td>
                                    <td><button class="edit-user-btn" data-user-id="{{ $user->id }}">Edit User</button></td>
                                </tr>
                            `;
                            });
                        } else {
                            tableBody = '<tr><td colspan="6">No users found.</td></tr>';
                        }

                        $('#userTableBody').html(tableBody);
                    }
                });
            }

            // Trigger search and filtering in real time
            $('#search, #department, #role').on('input change', function() {
                fetchUsers();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#search').val('');
                $('#department').val('');
                $('#role').val('');
                fetchUsers();
            });
        });
    </script>
@endsection






<style>
    .dashboard {
        padding: 10px;
    }

    .dashboard h1 {
        color: var(--ckcm-color4);

    }

    #filterForm {
        display: flex;
        gap: 10px;
        margin: 10px 0;
    }

    /* General Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #1e1e2f;
        color: white;
        padding: 20px;
        width: 400px;
        border-radius: 10px;
        text-align: left;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    /* Close Button */
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        border: none;
        background: none;
        color: white;
    }

    /* Form Inputs */
    label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #2a2a3a;
        color: white;
    }

    /* Role Container */
    .role-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 10px;
        border: 1px solid #ccc;
        cursor: pointer;
        min-height: 40px;
        border-radius: 5px;
        background-color: #2a2a3a;
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        background: gray;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        gap: 5px;
    }

    .remove-role {
        background: red;
        color: white;
        border: none;
        cursor: pointer;
        padding: 2px 5px;
        font-size: 12px;
        border-radius: 3px;
    }

    /* Dropdown */
    .dropdown-menu {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        width: 180px;
        max-height: 150px;
        overflow-y: auto;
        z-index: 100;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
    }

    .dropdown-item {
        padding: 8px;
        cursor: pointer;
        transition: background 0.2s ease-in-out;
        color: black;
    }

    .dropdown-item:hover {
        background-color: #f0f0f0;
    }

    /* Button Styling */
    button {
        background-color: #007bff;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.2s ease-in-out;
        width: 100%;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
