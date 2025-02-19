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
                <label for="instructor">Instructor</label>
                <select id="instructor" name="instructor">
                    <option value="" disabled selected>Select a Instructor</option>
                    @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->name }}">{{ $instructor->name }}</option>
                    @endforeach
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
            <div class="modal-footer">
                <button type="submit" class="save-btn"><i class="fa-solid fa-file-arrow-up"></i> Add Class</button>
                <button type="button" class="close-btn" id="cancelModal">Cancel</button>
            </div>
        </form>



    </div>
</div>



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

