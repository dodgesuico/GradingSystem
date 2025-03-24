<!--add class Modal -->
<div class="modal" id="classModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add a New Class</h2>
            <button class="close-btn" id="closeModal">&times;</button>
        </div>

        <form action="{{ route('classes.create') }}" method="POST">
            @csrf
            <div class="info-container">
                <label for="subject_code">Subject Code: <em>*Example: GEC 001*</em></label>
                <input type="text" id="subject_code" name="subject_code" required>
            </div>
            <div class="info-container">
                <label for="descriptive_title">Descriptive Title:</label>
                <input type="text" id="descriptive_title" name="descriptive_title" required>
            </div>
            <div class="info-container">
                <label for="units">Units</label>
                <select id="units" name="units">
                    <option value="" disabled selected>Select Units</option>
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}">{{ $i }} </option>
                    @endfor
                </select>
            </div>
            <div class="info-container">
                <label for="instructorSearch">Instructor</label>
                <input type="text" id="instructorSearch" name="instructor" class="form-control"
                    placeholder="Search for an instructor..." oninput="filterInstructors()">
                <div id="instructorDropdown" class="dropdown-menu" ></div>
            </div>
            <div class="info-container">
                <label for="academic_year">Academic Year</label>
                <select id="academic_year" name="academic_year">
                    <option value="" disabled selected>Select Academic Year</option>
                    @for ($year = date('Y'); $year <= date('Y') + 5; $year++)
                        <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                    @endfor
                </select>
            </div>
            <div class="info-container">
                <label for="academic_period">Academic Period</label>
                <select id="academic_period" name="academic_period">
                    <option value="" disabled selected>Select Academic Period</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
            <div class="info-container">
                <label for="schedule">Schedule: <em>*Example: Monday, Tuesday, Wednesday*</em></label>

                <input type="text" id="schedule" name="schedule" required>
            </div>
            <div class="info-container">
                <label for="status">Status: <em>*automatically active upon adding*</em></label>
                <input type="status" id="status" name="status" value="Active" readonly>
            </div>
            <div class="info-container">
                <label for="status">Password: <em>*for security purposes*</em></label>
                <input  id="password" name="password" ><button type="button" id="generate-password" onclick="generateRandomPassword()"> Generate Password</button>
            </div>

            <script>
                function generateRandomPassword(){
                    const characters = 'ABCDEFGHIJKLMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'
                    let password ='';
                    for (let i = 0; i < 10; i++){
                        password += characters.charAt(Math.floor(Math.random() * characters.length));
                    }
                    document.getElementById('password').value = password;
                }
            </script>

            <input type="hidden" name="added_by" value="{{ Auth::user()->name }}">

            <div class="modal-footer">
                <button type="submit" class="save-btn"><i class="fa-solid fa-file-arrow-up"></i> Add Class</button>
                <button type="button" class="close-btn" id="cancelModal">Cancel</button>
            </div>
        </form>



    </div>
</div>

<script>
    function filterInstructors() {
        let input = document.getElementById("instructorSearch").value.toLowerCase();
        let dropdown = document.getElementById("instructorDropdown");
        dropdown.innerHTML = ""; // Clear previous results

        if (input.trim() === "") {
            dropdown.style.display = "none";
            return;
        }

        let instructors = {!! json_encode($instructors) !!}; // Use Blade variable
        let filtered = instructors.filter(instructor =>
            instructor.name.toLowerCase().includes(input)
        );

        if (filtered.length === 0) {
            dropdown.style.display = "none";
            return;
        }

        filtered.forEach(instructor => {
            let option = document.createElement("div");
            option.classList.add("dropdown-item");
            option.textContent = instructor.name;
            option.onclick = function() {
                document.getElementById("instructorSearch").value = instructor.name;
                dropdown.style.display = "none";
            };
            dropdown.appendChild(option);
        });

        dropdown.style.display = "block";
    }
</script>

<!-- scripts -->
<!-- script for modal -->
<script>
    const modal = document.getElementById('classModal');
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelModalBtn = document.getElementById('cancelModal');

    openModalBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    cancelModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>


{{-- style for dropdow in searching student --}}
<style>
    .dropdown-menu {
        padding: 5px;
        gap: 5px;
        font-size: 1.1rem;
        text-align: center;

    }
    .dropdown-menu div{
        border-radius: 10px;
        padding: 5px;
        background: var(--color-blue);
        color: var(--color2);
        cursor: pointer;
        margin-bottom: 5px;
    }
</style>
