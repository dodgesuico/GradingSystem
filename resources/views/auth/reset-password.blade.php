<h1>Reset Password</h1>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="email" name="email" value="{{ old('email', request('email')) }}" required>
    <input type="password" name="password" placeholder="New password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm password" required>
    
    @error('email')
        <div>{{ $message }}</div>
    @enderror
    @error('password')
        <div>{{ $message }}</div>
    @enderror

    <button type="submit">Reset Password</button>
</form>