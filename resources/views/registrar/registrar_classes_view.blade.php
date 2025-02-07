@extends("layouts.registrar")

<title>@yield("title", "CKCM Grading | View Class Details")</title>

@section("content")


<div class="dashboard">
    <h1>Class Details</h1>
    <h2>{{ $class->descriptive_title }}</h2>
    <p><strong>Subject Code:</strong> {{ $class->subject_code }}</p>
    <p><strong>Instructor:</strong> {{ $class->instructor }}</p>
    <p><strong>Academic Period:</strong> {{ $class->academic_period }}</p>
    <p><strong>Schedule:</strong> {{ $class->schedule }}</p>
    <p><strong>Status:</strong> {{ $class->status }}</p>

    <a href="{{ route('registrar_classes') }}">Back to Classes</a>
</div>
@endsection

<style>
    .dashboard {
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .dashboard h1 {
        color: var(--ckcm-color4);
    }

    .dashboard h2 {
        color: var(--color6);
    }

    .dashboard p {
        font-size: 1.2rem;
        margin: 5px 0;
    }

    .dashboard a {
        color: var(--ckcm-color4);
        text-decoration: none;
        font-weight: bold;
    }
</style>
