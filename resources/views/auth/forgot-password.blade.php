@extends("layouts.auth_default")
<title>@yield("title", "Forgot Password | Grading System, CKCM, Inc.")</title>
@section("content")





<div class="container">
    <div class="login-box">

        <img style="align-self:center;" src="{{ asset('system_images/icon.png')}}" alt="">
        <p style="margin-bottom: 10px; color:var(--color5); font-size: 1.2rem; align-self:center;">Grading System | CKCM Inc. <em>v.1</em></p>
        <h1 style="text-align:center;">Forgot Password</h1>


        <form method="POST" action="{{ route('password.email') }}">
            <div class="input-group">
                <label for="">Email</label>
                @csrf
                <input type="email" name="email" placeholder="Enter your email" required>

            </div>
            <button type="submit" class="btn">Send Password Reset Link</button>
        </form>

        <!-- Success Message -->
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <!-- Error Message -->
        @error('email')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
        @enderror

        <a href="{{ route('login') }}" class="return-btn"><i class="fa-solid fa-person-walking-arrow-loop-left"></i> Return</a>
    </div>
</div>
@endsection


<style>

    .return-btn {
        margin-top: 20px;
        align-self: center;
        font-size: 1.2rem;
        color: var(--color5);
        text-decoration: none;
    }

    .return-btn:hover {
        color: var(--ckcm-color3);
    }
</style>