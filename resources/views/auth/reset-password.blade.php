@extends("layouts.auth_default")
<title>@yield("title", "Reset Password | Grading System, CKCM, Inc.")</title>
@section("content")



<div class="container">
    <div class="login-box">
        <img style="align-self:center;" src="{{ asset('system_images/icon.png')}}" alt="">
        <p style="margin-bottom: 10px; color:var(--color5); font-size: 1.2rem; align-self:center;">Grading System | CKCM Inc. <em>v.1</em></p>
        <h1 style="text-align:center">Reset Password</h1>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <div class="input-group" style="display:flex; flex-direction:column; gap:10px;">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="email" name="email" value="{{ old('email', request('email')) }}" required>
                <input type="password" name="password" placeholder="New password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm password" required>
            </div>

            <button class="btn" type="submit">Reset Password</button>
        </form>

        <div style="margin-top:10px;">.
            <!-- Success Message -->
            @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="message error">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

         
        </div>

    </div>
</div>
@endsection


<style>
    .message {
      
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
        text-align: center;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>