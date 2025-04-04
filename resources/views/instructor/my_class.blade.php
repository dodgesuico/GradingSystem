@extends('layouts.default')

@section('content')

    <div class="dashboard">

        <div class="header-container">

            <h1>My Classes</h1>
        </div>

        <style>
            .header-container {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
        </style>

        <div class="classes-header">
            <input type="text" id="searchInput" class="search" placeholder="🔎 Quick Search...">

        </div>



        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll("table tbody tr");

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                });
            });
        </script>




        <div style="display:flex; flex-direction:row; justify-content:space-between;width:100%;">
            <h2 style="margin:10px 0; ">Class List</h2>

        </div>


        <!-- table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Code</th>
                    <th>Descriptive Title</th>
                    <th>Instructor</th>
                    <th>Academic Peroiod</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            @foreach ($classes as $class)
                <tr>
                    <td>{{ $class->id }}</td>
                    <td style="border: 0; border-bottom: 1px solid var(--color7);">{{ $class->subject_code }}</td>
                    <td style="border: 0; border-bottom: 1px solid var(--color7);">{{ $class->descriptive_title }}</td>
                    <td style="border: 0; border-bottom: 1px solid var(--color7);">{{ $class->instructor }}</td>
                    <td style="border: 0; border-bottom: 1px solid var(--color7);">{{ $class->academic_period }}</td>
                    <td style="border: 0; border-bottom: 1px solid var(--color7);">{{ $class->schedule }}</td>

                    <td style="border: 0; border-bottom: 1px solid var(--color7);"
                        class="status {{ strtolower($class->status) }}">{{ $class->status }}</td>


                    <td style="text-align:center; background-color: var(--color9b);">
                        <!-- Edit Button -->
                        <a href="{{ route('class.show', $class->id) }}" class="view-btn"><i
                                class="fa-solid fa-up-right-from-square"></i> View Class</a>

                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>


@endsection



<style>
    .classes-header {
        display: flex;
        margin-top: 10px;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .empty-message {
        text-align: center;
        color: red;
        padding: 10px;
    }

    .add-btn {
        background: transparent;
        border: 0;
    }

    .add-btn:hover {
        background: transparent;

    }

    .dashboard {
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;


    }

    .dashboard h1 {
        color: var(--ckcm-color4)
    }

    .dashboard h2 {
        color: var(--color5);
    }
</style>
