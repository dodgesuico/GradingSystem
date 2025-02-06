@if (!Auth::check())
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@else
    @if (Auth::user()->role !== 'student')
        <script>
            window.history.back();
        </script>
    @endif
@endif



hello to ckcm Grading

@if (Auth::check())
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endif