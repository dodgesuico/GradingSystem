@extends("layouts.teacher")
<title>@yield("title", "CKCM Grading | Teacher Home")</title>
@section("content")

<div>
    @if (Auth::check())
        <p>Welcome, {{ Auth::user()->name }}!</p>
        <p>Your Role is, {{ Auth::user()->role }}!</p>
    @endif
</div>


@endsection

