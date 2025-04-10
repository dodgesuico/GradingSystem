@extends('layouts.auth_default')

<title>@yield('title', 'Login | Grading System, CKCM, Inc.')</title>

@section('content')




    <div class="container">
        <div class="login-box">
            <img style="align-self:center;" src="system_images/icon.png" alt="">
            <p style="margin-bottom: 10px; color:var(--color5); font-size: 1.2rem; align-self:center;">Grading System | CKCM
                Inc. <em>v.1</em></p>

            <h1 class="roboto-flex-bold" style="align-self:center;">Sign in your Account</h1>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="login" placeholder="name@email.com" required>
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <!-- Register & Forgot Password Links -->
                <div class="links">

                    <!-- Remember Me -->
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>


                    <!-- <a href="{{ route('register') }}">Create an account here</a> | -->
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>

                <!-- Submit Button with Loading Spinner -->
                <button type="submit" class="btn" id="signInButton">
                    <span class="spinner" id="spinner" style="display: none;"></span>
                    <span id="buttonText">Sign in</span>
                </button>

                <!-- JavaScript for Loading Spinner -->
                <script>
                    document.getElementById("loginForm").addEventListener("submit", function() {
                        let button = document.getElementById("signInButton");
                        let spinner = document.getElementById("spinner");
                        let buttonText = document.getElementById("buttonText");

                        button.disabled = true; // Disable button to prevent multiple clicks
                        spinner.style.display = "inline-block"; // Show spinner
                        buttonText.textContent = "Signing in..."; // Change button text
                    });
                </script>

                <!-- CSS for Spinner -->
                <style>
                    .spinner {
                        width: 16px;
                        height: 16px;
                        border: 2px solid #fff;
                        border-top: 2px solid transparent;
                        border-radius: 50%;
                        display: inline-block;
                        margin-right: 8px;
                        animation: spin 0.8s linear infinite;
                    }

                    @keyframes spin {
                        0% {
                            transform: rotate(0deg);
                        }

                        100% {
                            transform: rotate(360deg);
                        }
                    }
                </style>





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
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </form>

            <div style="margin-top:10px;">
                <em style="color:var(--color5)">By clicking “Sign in”, you agree to our Terms of Service and Privacy
                    Statement </em>
            </div>

            <div class="trademark-container">
                <p style="margin-top:20px; color:var(--color6)"><strong class="gradient-text" style="font-size:1rem;">CKCM
                        Grading System</strong> is a Trademark of <a href="https://khemarkocariza.ct.ws/"
                        target="_blank">Khemark Ocariza</a> and <a href="https://myporfolioforlife.netlify.app/#about"
                        target="_blank">Dodge Nicholson Suico</a>.
                    Copyright © 2025-<?php echo date('Y'); ?> CKCM Technologies, LLC.</p>

            </div>

        </div>

    </div>


@endsection





<style>
    .trademark-container a {
        text-decoration: none;
        color: var(--ckcm-color4);
    }
    .trademark-container a:hover {
        text-decoration: none;
        color: var(--ckcm-color3);
    }

    .alert {
        width: 100%;

    }
</style>
