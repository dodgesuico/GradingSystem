@extends("layouts.auth_default")

@section("Title", "Login")

@section("content")






<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<div class="container">
    <div class="login-box">

        <img src="system_images/icon.png" alt="">
        <p style="margin-bottom: 10px; color:var(--color5); font-size: 1.2rem;">Grading System | CKCM Inc. <em>v.1</em></p>

        <h1>Sign in your Account</h1>
        <form action="{{ route('login.post') }}" method="POST">
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

            <!-- Submit Button -->
            <button type="submit" class="btn">Sign in</button>


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
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
            </div>
        </form>




    </div>

</div>

@endsection










<style>
    /* General Styling */
    body {
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        background-color: #0F172A;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='250' viewBox='0 0 1080 900'%3E%3Cg fill-opacity='0.01'%3E%3Cpolygon fill='%23444' points='90 150 0 300 180 300'/%3E%3Cpolygon points='90 150 180 0 0 0'/%3E%3Cpolygon fill='%23AAA' points='270 150 360 0 180 0'/%3E%3Cpolygon fill='%23DDD' points='450 150 360 300 540 300'/%3E%3Cpolygon fill='%23999' points='450 150 540 0 360 0'/%3E%3Cpolygon points='630 150 540 300 720 300'/%3E%3Cpolygon fill='%23DDD' points='630 150 720 0 540 0'/%3E%3Cpolygon fill='%23444' points='810 150 720 300 900 300'/%3E%3Cpolygon fill='%23FFF' points='810 150 900 0 720 0'/%3E%3Cpolygon fill='%23DDD' points='990 150 900 300 1080 300'/%3E%3Cpolygon fill='%23444' points='990 150 1080 0 900 0'/%3E%3Cpolygon fill='%23DDD' points='90 450 0 600 180 600'/%3E%3Cpolygon points='90 450 180 300 0 300'/%3E%3Cpolygon fill='%23666' points='270 450 180 600 360 600'/%3E%3Cpolygon fill='%23AAA' points='270 450 360 300 180 300'/%3E%3Cpolygon fill='%23DDD' points='450 450 360 600 540 600'/%3E%3Cpolygon fill='%23999' points='450 450 540 300 360 300'/%3E%3Cpolygon fill='%23999' points='630 450 540 600 720 600'/%3E%3Cpolygon fill='%23FFF' points='630 450 720 300 540 300'/%3E%3Cpolygon points='810 450 720 600 900 600'/%3E%3Cpolygon fill='%23DDD' points='810 450 900 300 720 300'/%3E%3Cpolygon fill='%23AAA' points='990 450 900 600 1080 600'/%3E%3Cpolygon fill='%23444' points='990 450 1080 300 900 300'/%3E%3Cpolygon fill='%23222' points='90 750 0 900 180 900'/%3E%3Cpolygon points='270 750 180 900 360 900'/%3E%3Cpolygon fill='%23DDD' points='270 750 360 600 180 600'/%3E%3Cpolygon points='450 750 540 600 360 600'/%3E%3Cpolygon points='630 750 540 900 720 900'/%3E%3Cpolygon fill='%23444' points='630 750 720 600 540 600'/%3E%3Cpolygon fill='%23AAA' points='810 750 720 900 900 900'/%3E%3Cpolygon fill='%23666' points='810 750 900 600 720 600'/%3E%3Cpolygon fill='%23999' points='990 750 900 900 1080 900'/%3E%3Cpolygon fill='%23999' points='180 0 90 150 270 150'/%3E%3Cpolygon fill='%23444' points='360 0 270 150 450 150'/%3E%3Cpolygon fill='%23FFF' points='540 0 450 150 630 150'/%3E%3Cpolygon points='900 0 810 150 990 150'/%3E%3Cpolygon fill='%23222' points='0 300 -90 450 90 450'/%3E%3Cpolygon fill='%23FFF' points='0 300 90 150 -90 150'/%3E%3Cpolygon fill='%23FFF' points='180 300 90 450 270 450'/%3E%3Cpolygon fill='%23666' points='180 300 270 150 90 150'/%3E%3Cpolygon fill='%23222' points='360 300 270 450 450 450'/%3E%3Cpolygon fill='%23FFF' points='360 300 450 150 270 150'/%3E%3Cpolygon fill='%23444' points='540 300 450 450 630 450'/%3E%3Cpolygon fill='%23222' points='540 300 630 150 450 150'/%3E%3Cpolygon fill='%23AAA' points='720 300 630 450 810 450'/%3E%3Cpolygon fill='%23666' points='720 300 810 150 630 150'/%3E%3Cpolygon fill='%23FFF' points='900 300 810 450 990 450'/%3E%3Cpolygon fill='%23999' points='900 300 990 150 810 150'/%3E%3Cpolygon points='0 600 -90 750 90 750'/%3E%3Cpolygon fill='%23666' points='0 600 90 450 -90 450'/%3E%3Cpolygon fill='%23AAA' points='180 600 90 750 270 750'/%3E%3Cpolygon fill='%23444' points='180 600 270 450 90 450'/%3E%3Cpolygon fill='%23444' points='360 600 270 750 450 750'/%3E%3Cpolygon fill='%23999' points='360 600 450 450 270 450'/%3E%3Cpolygon fill='%23666' points='540 600 630 450 450 450'/%3E%3Cpolygon fill='%23222' points='720 600 630 750 810 750'/%3E%3Cpolygon fill='%23FFF' points='900 600 810 750 990 750'/%3E%3Cpolygon fill='%23222' points='900 600 990 450 810 450'/%3E%3Cpolygon fill='%23DDD' points='0 900 90 750 -90 750'/%3E%3Cpolygon fill='%23444' points='180 900 270 750 90 750'/%3E%3Cpolygon fill='%23FFF' points='360 900 450 750 270 750'/%3E%3Cpolygon fill='%23AAA' points='540 900 630 750 450 750'/%3E%3Cpolygon fill='%23FFF' points='720 900 810 750 630 750'/%3E%3Cpolygon fill='%23222' points='900 900 990 750 810 750'/%3E%3Cpolygon fill='%23222' points='1080 300 990 450 1170 450'/%3E%3Cpolygon fill='%23FFF' points='1080 300 1170 150 990 150'/%3E%3Cpolygon points='1080 600 990 750 1170 750'/%3E%3Cpolygon fill='%23666' points='1080 600 1170 450 990 450'/%3E%3Cpolygon fill='%23DDD' points='1080 900 1170 750 990 750'/%3E%3C/g%3E%3C/svg%3E");
    }






    /* Centered Login Box */
    .container {

        display: flex;
        width: 100%;
        height: 100%;
        padding: 0 150px;
        justify-content: left;
    }




    .login-box {
        padding: 150px 20px 0 20px;
        width: 350px;
        background-color: var(--ckcm-color1);
        border: 1px solid var(--ckcm-color2 );
    }

    .login-box img {
        width: 90px;
        margin-bottom: 40px
    }

    /* Heading */
    h1 {
        margin-bottom: 20px;
        color: var(--ckcm-color3);



    }

    /* Input Group */
    .input-group {
        text-align: left;
        margin-bottom: 15px;
    }

    .input-group label {
        font-size: 14px;
        color: var(--color4);
        display: block;
        margin-bottom: 5px;
    }

    .input-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid var(--ckcm-color2);
        border-radius: 5px;
        font-size: 1.4rem;
        background-color: var(--ckcm-color2);
        color: var(--color1);
    }

    .input-group input:focus {
        background-color: var(--ckcm-color1);
        outline: 1px solid var(--ckcm-color3);
    }

    /* Remember Me */
    .remember-me {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color6);
    }

    .remember-me input {
        margin-right: 5px;
    }

    /* Button with Gradient */
    .btn {
        background: linear-gradient(45deg, var(--ckcm-color3), var(--ckcm-color4));
        /* Blue gradient */
        color: white;
        padding: 10px;
        border: none;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;

    }

    /* Hover Effect */
    .btn:hover {
        transition: background 0.3s ease-in-out;
        background: linear-gradient(45deg, var(--ckcm-color4), var(--ckcm-color3));
        /* Darker blue gradient */
    }





    /* Links */
    .links {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        margin: 15px 0;
        font-size: 1.3rem;
    }

    .links a {

        color: var(--ckcm-color4);
        text-decoration: none;
    }

    .links a:hover {
        text-decoration: underline;
    }

    /* Error Message */

    .message-container {
        margin-top: 10px;
        text-align: center;
        font-size: 1.2rem;
        text-decoration: none;
        color: var(--color-red);
    }
</style>




<!-- Place this in your CSS file or inside <style> -->
<style>
    /* Hide default checkbox */
    #remember {
        display: none;
    }

    /* Custom checkbox container */
    .remember-me label {
        position: relative;
        padding-left: 20px;
        cursor: pointer;

        user-select: none;
    }

    /* Custom checkbox box */
    .remember-me label::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        border: 2px solid var(--ckcm-color2);
        border-radius: 4px;
        background-color: var(--ckcm-color1);
    }

    /* Checked state */
    #remember:checked+label::before {
        background-color: var(--ckcm-color2);
        border-color: var(--ckcm-color2);
    }

    /* Add checkmark */
    #remember:checked+label::after {
        content: "✔";
        position: absolute;
        left: 2px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-weight: bold;
    }
</style>