@extends("layouts.registrar")
<title>@yield("title", "CKCM Grading | Registrar Classes")</title>
@section("content")

<div class="dashboard">


    <div class="classes-header">
        <input type="text" id="searchInput" placeholder="🔎 Search...">
        <button class="add-btn" id="openModal"><i class="fa-solid fa-plus"></i> Add Classes</button>

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


    <div class="message-container">
        @if (session()->has("success"))
        <div class="alert alert-success">
            {{ session()->get("success") }}
        </div>
        @endif

        @if (session()->has("error"))
        <div class="alert alert-danger">
            {{ session()->get("error") }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
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
            <td>{{ $class->subject_code}}</td>
            <td>{{ $class->descriptive_title}}</td>
            <td>{{ $class->instructor }}</td>
            <td>{{ $class->academic_period }}</td>
            <td>{{ $class->schedule }}</td>
            <td>{{ $class->status }}</td>
            <td style="text-align:center">
                <!-- Edit Button -->
                <a href="{{ route('class.show', $class->id) }}">View Class</a> |
                <button class="edit-btn" onclick="openEditClassModal({{ $class->id }})"><i class="fa-solid fa-pen-to-square"></i> Edit</button> |
                <button class="delete-btn" onclick="openDeleteClassModal({{ $class->id }})"><i class="fa-solid fa-trash"></i> Delete</button>
            </td>
        </tr>

        @include('registrar.registrar_classes_edit_and_delete')


        @endforeach
        </tbody>
    </table>



</div>


@include('registrar.registrar_classes_add')


@endsection

















<style>
    .message-container {
        display: flex;
        justify-content: left;
        align-items: center;
        width: 100%;
    }

    .message-container .alert {
        font-size: 1.2rem;
    }

    .alert-success {
        color: var(--color-green);
    }

    .alert-danger {
        color: var(--color-red);
    }
</style>


<style>
    .classes-header input {
        padding: 7px;
        background-color: var(--ckcm-color2);
        width: 200px;
        color: var(--color1);
        border: 1px solid var(--color6);
        outline: none;
        /* Removes default focus outline */
        transition: border 0.3s ease-in-out;
        border-radius: 5px;
    }

    .classes-header input:focus {
        border: 1px solid var(--ckcm-color1);
        box-shadow: 0 0 5px var(--color-blue);
    }

    .add-btn {
        background-color: transparent;
    }
</style>


<!-- style for add class -->
<style>
    /* Modal Background */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
    }

    /* Modal Content */
    .modal-content {
        height: fit-content;
        border: 1px solid var(--color5);
        background-color: var(--ckcm-color1);
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 30%;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .modal-header h2 {
        color: var(--color1);
    }

    .modal-header,
    .modal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }



    .info-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-top: 5px;
    }

    .info-container label {
        color: var(--color3);
        font-size: 1.2rem;
    }

    .info-container label em {
        color: var(--color4);
        font-size: 1rem;
    }

    .info-container input,
    .info-container select {
        padding: 5px;
        background-color: var(--color1);
        border-radius: 5px;
        border: 1px solid var(--color6);
    }

    .modal-footer {
        text-align: right;
        margin-top: 10px;
    }

    .modal-add-btn:hover {
        background-color: var(--color-blue);
    }

    .close-btn .close {
        background: #f44336;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>


















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
        text-decoration: underline;
    }

    .dashboard {
        padding: 10px;
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