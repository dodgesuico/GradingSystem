
<head>
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>


<div class="container">
    <div class="login-box">
        <h2>Register</h2>

        <form action="{{ route('register.post') }}" method="POST">

            @csrf

               <!-- Email Input -->
            <div class="input-group">
                <label for="name">Student ID</label>
                <input type="number" id="email" name="studentID" required>
            </div>

            <!-- Email Input -->
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" id="email" name="name" required>
            </div>


            <div class="input-group">
                <label for="email">Gender</label>
                <input type="text" id="email" name="gender" required>
            </div>

            <!-- Email Input -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>


            <div class="input-group">
                <label for="department">Program</label>
                <select id="department" name="department">
                    <option value="" disabled selected>Select a Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Password Input -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Password Input -->
            <div class="input-group">
                <label for="password">Confirm Password</label>
                <input type="password" id="password" name="confirm_password" required>
            </div>

            <!-- Password Input -->
            <div class="input-group">
                <label for="role">Select Role</label>
                <select name="role" id="role" required>
                    <option value="student">Student</option>
                    <option value="registrar">Registrar</option>
                    <option value="dean">Dean</option>
                    <option value="instructor">Instructor</option>
                </select>
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn">Submit</button>

            <!-- Register & Forgot Password Links -->
            <div class="links">
                <a href="{{ route('login') }}">Already have an account?</a> |
                <a href="#">Forgot password?</a>
            </div>
        </form>

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
</div>



