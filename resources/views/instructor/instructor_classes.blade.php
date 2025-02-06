@extends("layouts.instructor")
<title>@yield("title", "CKCM Grading | instructor Classes")</title>
@section("content")

<div class="dashboard">
    <h1>Instructor Classes</h1>
    <h2>here's your classes</h2>
</div>


@endsection


<style>
    .dashboard {
        margin-top: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 10px;
    }

    .dashboard h1 {
        color: var(--ckcm-color4)
    }

    .dashboard h2 {
        color: var(--color6);
    }
</style>