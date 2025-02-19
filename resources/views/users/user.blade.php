@extends('layouts.default')

<title>@yield('title', 'CKCM Grading | View Users')</title>

@section('content')




<div class="dashboard">
    <h1>Users List</h1>

    <!-- Search and Filter Form -->
    <form id="filterForm">
        <input type="text" name="search" id="search" class="search" placeholder="🔎 Search by name or email" value="{{ request('search') }}">

        <select name="department" id="department">
            <option value="">Filter by Department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                    {{ $dept }}
                </option>
            @endforeach
        </select>

        <select name="role" id="role">
            <option value="">Filter by Role</option>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                    {{ $role }}
                </option>
            @endforeach
        </select>

        <button type="button" id="resetFilters">Reset</button>
    </form>

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
                </tr>
            @empty
                <tr>
                    <td colspan="6">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

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
    .dashboard{
        padding: 10px;
    }

    .dashboard h1{
        color: var(--ckcm-color4);

    }

    #filterForm{
        display: flex;
        gap: 10px;
        margin: 10px 0;
    }
</style>

