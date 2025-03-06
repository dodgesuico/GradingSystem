@extends('layouts.default')
@section('content')
    <div class="dashboard">
        @if (Auth::check())
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <h2>Your Role is, {{ Auth::user()->role }}!</h2>
            @if (Auth::check() && str_contains(Auth::user()->role, 'student'))
            <a href="{{ route('my_grades') }}" ><i class="fa-solid fa-arrow-up-right-from-square"></i> Go to My Grades</a>
            @endif
        @endif
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

    a{
        font-size: 1.2rem;
        color: var(--color1);
        text-decoration: none;
    }
    a:hover{
        color: var(--ckcm-color3);
    }

    .dashboard h1 {
        color: var(--ckcm-color4)
    }

    .dashboard h2 {
        color: var(--color6);
    }

    @media (max-width: 480px) {
        .dashboard h1 {
            color: var(--ckcm-color4)
        }

        .dashboard h2 {
            color: var(--color6);
        }
    }

    @media (max-width: 768px) {
        .dashboard {
            margin-top: 200px;
        }
        .dashboard h1 {
            color: var(--ckcm-color4);
            font-size: 1.5rem;
        }

        .dashboard h2 {
            color: var(--color6);
            font-size: 1.2rem;
        }

    }
</style>
