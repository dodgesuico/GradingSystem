Hello to Laravel

<!-- welcome.blade.php -->
@if (Auth::check())
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
@endif