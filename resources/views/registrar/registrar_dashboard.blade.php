@extends("layouts.registrar")
<title>@yield("title", "CKCM Grading | Dean Home")</title>
@section("content")

<div class="dashboard">
    @if (Auth::check())
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <h2>Your Role is, {{ Auth::user()->role }}!</h2>
    @endif
</div>


@endsection


<style>
    .dashboard{
        margin-top: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 10px;
    }

    .dashboard h1{
        color: var(--ckcm-color4)
    }
    .dashboard h2{
        color: var(--color6);
    }
</style>