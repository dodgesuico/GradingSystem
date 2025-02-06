

    <h1>Forgot Password</h1>
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" required>
        @error('email')
            <div>{{ $message }}</div>
        @enderror
        <button type="submit">Send Password Reset Link</button>
    </form>
