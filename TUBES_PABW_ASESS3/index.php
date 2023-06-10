<!DOCTYPE html>
<html>
<head>
    <title>Data Table Example with CRUD</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
</head>
<body>
    <table id="dataTable">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Location</th>
                <th>Brief Description</th>
                <th>Detailed Description</th>
                <th>Required Experience</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Add Modal -->
    <div id="addModal" style="display: none;">
        <h3>Add Job</h3>
        <form id="addForm">
            <label for="addJobTitle">Job Title:</label>
            <input type="text" id="addJobTitle" name="job_title" required><br>

            <label for="addLocation">Location:</label>
            <input type="text" id="addLocation" name="location" required><br>

            <label for="addBriefDescription">Brief Description:</label>
            <textarea id="addBriefDescription" name="brief_description" required></textarea><br>

            <label for="addDetailedDescription">Detailed Description:</label>
            <textarea id="addDetailedDescription" name="detailed_description" required></textarea><br>

            <label for="addRequiredExperience">Required Experience:</label>
            <input type="text" id="addRequiredExperience" name="required_experience" required><br>

            <input type="submit" value="Add">
        </form>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" style="display: none;">
        <h3>Edit Job</h3>
        <form id="editForm">
            <input type="hidden" id="editId" name="id">

            <label for="editJobTitle">Job Title:</label>
            <input type="text" id="editJobTitle" name="job_title" required><br>

            <label for="editLocation">Location:</label>
            <input type="text" id="editLocation" name="location" required><br>

            <label for="editBriefDescription">Brief Description:</label>
            <textarea id="editBriefDescription" name="brief_description" required></textarea><br>

            <label for="editDetailedDescription">Detailed Description:</label>
            <textarea id="editDetailedDescription" name="detailed_description" required></textarea><br>

            <label for="editRequiredExperience">Required Experience:</label>
            <input type="text" id="editRequiredExperience" name="required_experience" required><br>

            <input type="submit" value="Update">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                ajax: {
                    url: 'localhost:80/TUBES_PABW_ASESS3/jobapp/api_tampil_all.php',
                    dataType: 'json',
                    dataSrc: ''
                },
                columns: [
                    { data: 'job_title' },
                    { data: 'location' },
                    { data: 'brief_description' },
                    { data: 'detailed_description' },
                    { data: 'required_experience' },
                    { 
                        data: null,
                        render: function(data, type, row) {
                            return '<button onclick="editJob(' + data.id + ')">Edit</button> ' +
                                   '<button onclick="deleteJob(' + data.id + ')">Delete</button>';
                        }
                    }
                ]
            });

            // Add job form submission
            $('#addForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'localhost:80/TUBES_PABW_ASESS3/jobapp/api_tambah.php',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function() {
                        table.ajax.reload();
                        $('#addModal').hide();
                    },
                    error: function() {
                        alert('Error adding job!');
                    }
                });
            });

            // Edit job
            window.editJob = function(id) {
                var job = table.row('#' + id).data();
                $('#editId').val(job.id);
                $('#editJobTitle').val(job.job_title);
                $('#editLocation').val(job.location);
                $('#editBriefDescription').val(job.brief_description);
                $('#editDetailedDescription').val(job.detailed_description);
                $('#editRequiredExperience').val(job.required_experience);
                $('#editModal').show();
            };

            // Edit job form submission
            $('#editForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var jobId = $('#editId').val();
                $.ajax({
                    url: 'localhost:80/TUBES_PABW_ASESS3/jobapp/api_edit.php' + jobId,
                    type: 'PUT',
                    dataType: 'json',
                    data: formData,
                    success: function() {
                        table.ajax.reload();
                        $('#editModal').hide();
                    },
                    error: function() {
                        alert('Error updating job!');
                    }
                });
            });

            // Delete job
            window.deleteJob = function(id) {
                if (confirm('Are you sure you want to delete this job?')) {
                    $.ajax({
                        url: 'localhost:80/TUBES_PABW_ASESS3/jobapp/api_hapus.php' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function() {
                            table.ajax.reload();
                        },
                        error: function() {
                            alert('Error deleting job!');
                        }
                    });
                }
            };
        });
    </script>
</body>
</html>
