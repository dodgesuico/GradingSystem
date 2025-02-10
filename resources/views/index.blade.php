@if (!Auth::check())
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@else
    @if (Auth::user()->role == 'student')
        <div> Hello, {{ Auth::user()->role }} </div>
    @elseif (Auth::user()->role == 'registrar')
        <div> Hello, {{ Auth::user()->role }} </div>
    @else (Auth::user()->role == 'instructor')
        <div> Hello, {{ Auth::user()->role }}</div>
    @endif

@endif



hello to ckcm Grading

@if (Auth::check())
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endif