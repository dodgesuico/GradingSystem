@extends("layouts.default")

@section("Title", "Login")

@section("content")


<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<div class="container">
    <div class="login-box">
        <h2>Login</h2>

        @if (session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        <form action="" method="POST">
            @csrf

            <!-- Email Input -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Password Input -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn">Login</button>

            <!-- Register & Forgot Password Links -->
            <div class="links">
                <a href="{{ route('register') }}">Create an account here</a> | 
                <a href="#">Forgot password?</a> 
            </div>
        </form>
    </div>
</div>
@endsection
