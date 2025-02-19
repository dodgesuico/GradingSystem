        <!-- Edit Class Modal -->
        <div id="editClassModal{{ $class->id }}" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Class - {{ $class->subject_code }}</h5>
                        <button type="button" class="close" onclick="closeEditClassModal({{ $class->id }})">&times;</button>
                </div>
                <form action="{{ route('classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="info-container">
                            <label for="subject_code">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" value="{{ $class->subject_code }}" required>
                        </div>
                        <div class="info-container">
                            <label for="descriptive_title">Descriptive Title</label>
                            <input type="text" class="form-control" id="descriptive_title" name="descriptive_title" value="{{ $class->descriptive_title }}" required>
                        </div>
                        <div class="info-container">
                            <label for="instructor">Instructor</label>
                            <select id="instructor" name="instructor">
                                <option value="" disabled>Select Instructor</option>
                                @foreach ($instructors as $instructor)
                                <option value="{{ $instructor->name }}" {{ (isset($class) && $class->instructor == $instructor->name) ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="info-container">
                            <label for="academic_period">Academic Period</label>
                            <select id="academic_period" name="academic_period">
                                <option value="" disabled>Select Academic Period</option>
                                <option value="1st Semester" {{ (isset($class) && $class->academic_period == '1st Semester') ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd Semester" {{ (isset($class) && $class->academic_period == '2nd Semester') ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer" {{ (isset($class) && $class->academic_period == 'Summer') ? 'selected' : '' }}>Summer</option>
                            </select>
                        </div>

                        <div class="info-container">
                            <label for="schedule">Schedule</label>
                            <input type="text" class="form-control" id="schedule" name="schedule" value="{{ $class->schedule }}" required>
                        </div>

                        <div class="info-container">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Active" {{ $class->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Ended" {{ $class->status == 'Ended' ? 'selected' : '' }}>Ended</option>
                                <option value="Dropped" {{ $class->status == 'Drop' ? 'selected' : '' }}>Dropped</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="save-btn"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                        <button type="button" class="btn btn-secondary" onclick="closeEditClassModal({{ $class->id }})">Close</button>

                    </div>
                </form>
            </div>
        </div>


        <!-- Delete Modal for each class -->
        <div id="deleteClassModal{{ $class->id }}" class="modal">
            <div class="modal-content">
                <h2 style="color:var(--color1); text-align:center;margin-bottom:1.5rem;">Delete Class</h2>
                <p style="color:var(--color-red);font-size:1.5rem; text-align:center;">
                    Are you sure you want to delete this class?
                </p>
                <p style="color:var(--color5);font-size:1.2rem; text-align:center;">
                    Class ID: {{ $class->id }}
                </p>

                <!-- Checkbox to confirm deletion -->
                <div style="display:flex;justify-content:center; text-align:center; margin: 10px 0; align-items:center; gap:10px;">
                    <input type="checkbox" id="confirmDelete{{ $class->id }}" onchange="toggleDeleteButton({{ $class->id }})">
                    <label style="color:var(--color6);" for="confirmDelete{{ $class->id }}">I am sure to delete this</label>
                </div>

                <div class="modal-footer">
                    <button class="btn-cancel" onclick="closeDeleteClassModal({{ $class->id }})">Cancel</button>

                    <form method="POST" action="{{ route('classes.destroy', $class->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn disabled-btn" id="deleteBtn{{ $class->id }}" disabled>
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- JavaScript to enable/disable delete button -->
        <script>
            function toggleDeleteButton(classId) {
                const checkbox = document.getElementById("confirmDelete" + classId);
                const deleteBtn = document.getElementById("deleteBtn" + classId);

                if (checkbox.checked) {
                    deleteBtn.disabled = false;
                    deleteBtn.classList.remove("disabled-btn");
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.classList.add("disabled-btn");
                }
            }
        </script>

        <!-- CSS for the disabled delete button -->
        <style>
            .disabled-btn {
                background-color: #ccc !important;
                /* Greyed out */
                cursor: not-allowed !important;
                /* Unclickable */
                opacity: 0.6;
                /* Lower visibility */
            }
        </style>


        <script>
            // Open the Edit Class modal
            function openEditClassModal(classId) {
                // Select the modal using the classId
                var modal = document.getElementById('editClassModal' + classId);
                modal.style.display = 'flex'; // Show the modal
            }

            // Close the Edit Class modal
            function closeEditClassModal(classId) {
                // Select the modal using the classId
                var modal = document.getElementById('editClassModal' + classId);
                modal.style.display = 'none'; // Hide the modal
            }

            function openDeleteClassModal(classId) {
                const modal = document.getElementById('deleteClassModal' + classId);
                modal.style.display = 'flex';
            }

            function closeDeleteClassModal(classId) {
                const modal = document.getElementById('deleteClassModal' + classId);
                modal.style.display = 'none';
            }

            // Close the modal when clicking outside of it
            window.onclick = function(event) {
                var modals = document.querySelectorAll('.modal');
                modals.forEach(function(modal) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                });
            }
        </script>
