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
                <div class="alert alert-danger" style="margin: 0 0 10px 0;">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="margin: 0 0 10px 0;">
                    <strong>Error!</strong> Please check the following issues:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="margin: 0 0 10px 0;">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif
        </div>


        <!-- Users Table -->
        <table class="user-table">
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
                        <td>
                            @foreach (explode(',', $user->role) as $index => $role)
                                <span class="display-role-badge">{{ trim($role) }}</span>
                                @if (!$loop->last)
                                    <span class="invisible-comma">,</span>
                                @endif
                            @endforeach
                        </td>

                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td style="text-align: center;">
                            <button class="edit-btn" data-user-id="{{ $user->id }}"><i
                                    class="fa-solid fa-pen-to-square"></i> Edit User</button>
                            <button class="view-btn"><i
                                class="fa-solid fa-pen-to-square"></i> View User</button>
                        </td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <script>
            $(document).ready(function() {
                $(".display-role-badge").each(function() {
                    let role = $(this).text().trim().toLowerCase();
                    if (role === "admin") $(this).css("background-color", "#003049");
                    else if (role === "student") $(this).css("background-color", "#588157");
                    else if (role === "dean") $(this).css("background-color", "#d62828");
                    else if (role === "instructor") $(this).css("background-color", "#f77f00");
                    else if (role === "registrar") $(this).css("background-color", "#9c27b0");
                });
            });
        </script>
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
                <button type="submit" style="margin-top: 10px;"class="save-btn"><i class="fa-solid fa-floppy-disk"></i>
                    Save Changes</button>
            </form>
        </div>
    </div>






    <script>
        $(document).ready(function() {
            $(document).on("click", ".edit-btn", function() {
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
                                let roleBadges = user.role.split(',').map(role => `
                                    <span class="display-role-badge">${role.trim()}</span>
                                `).join('<span class="invisible-comma">,</span>'); // Preserve hidden commas

                                tableBody += `
                                    <tr>
                                        <td>${user.id}</td>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.department}</td>
                                        <td>${roleBadges}</td>
                                        <td>${new Date(user.created_at).toISOString().split('T')[0]}</td>
                                        <td style="text-align: center;">
                                            <button class="edit-btn" data-user-id="${user.id}">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit User
                                            </button>
                                            <button class="edit-btn"><i
                                                    class="fa-solid fa-pen-to-square"></i> View User</button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            tableBody = '<tr><td colspan="7">No users found.</td></tr>';
                        }

                        // ✅ Update table content
                        $('#userTableBody').html(tableBody);

                        // ✅ Apply role badge colors in real-time
                        $(".display-role-badge").each(function() {
                            let role = $(this).text().trim().toLowerCase();
                            if (role === "admin") $(this).css({
                                "background-color": "#003049",
                                "color": "#fff"
                            });
                            else if (role === "student") $(this).css({
                                "background-color": "#588157",
                                "color": "#fff"
                            });
                            else if (role === "dean") $(this).css({
                                "background-color": "#d62828",
                                "color": "#fff"
                            });
                            else if (role === "instructor") $(this).css({
                                "background-color": "#f77f00",
                                "color": "#fff"
                            });
                            else if (role === "registrar") $(this).css({
                                "background-color": "#9c27b0",
                                "color": "#fff"
                            });
                        });
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

            // ✅ Initial fetch to display users on page load
            fetchUsers();
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
    .modal-content label {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 5px 0;
        display: block;
    }

    .modal-content input,
    .modal-content select {
        width: 100%
    }



    /* Role Container */
    .role-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 5px;
        border: 1px solid var(--color7);
        cursor: pointer;

        border-radius: 5px;
        background-color: var(--ckcm-color2);
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        background: var(--ckcm-color1);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        gap: 5px;
    }

    .invisible-comma {
        visibility: hidden;
        /* Makes the comma invisible but still keeps the space */
        /* Ensures a slight spacing effect */
    }

    .display-role-badge {
        display: inline-block;
        color: #fff;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin: 0;
        white-space: nowrap;
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
        background: var(--ckcm-color1);
        border: 1px solid #ccc;
        width: 92%;
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
        color: var(--color1);
    }

    .dropdown-item:hover {
        background: var(--hover-background-color);
    }

    /* Button Styling */

    .user-table {
        margin-top: 0;
    }

    .user-table,
    .user-table th,
    .user-table td {
        border-left: 0;
        border-right: 0;
    }
</style>
