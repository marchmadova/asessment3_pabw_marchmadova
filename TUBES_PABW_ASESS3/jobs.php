<!DOCTYPE html>
<html>
<head>
  <title>Job List</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    h1, h2 {
      color: #333;
    }

    form {
      margin-bottom: 20px;
    }

    label {
      display: inline-block;
      width: 150px;
    }

    input[type="text"],
    textarea,
    input[type="number"] {
      width: 300px;
      padding: 5px;
    }

    button {
      padding: 5px 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
</head>
<body>
  <h1>Job List</h1>

  <h2>Search Job</h2>
  <form id="search-form">
    <input type="text" id="search-input" placeholder="Search...">
  </form>

  <h2>Add Job</h2>
  <form id="add-job-form" method="POST">
    <label for="job_title">Job Title:</label>
    <input type="text" name="job_title" required>
    <br><br>
    <label for="location">Location:</label>
    <input type="text" name="location" required>
    <br><br>
    <label for="brief_description">Brief Description:</label>
    <textarea name="brief_description" required></textarea>
    <br><br>
    <label for="detailed_description">Detailed Description:</label>
    <textarea name="detailed_description" required></textarea>
    <br><br>
    <label for="required_experience">Required Experience:</label>
    <input type="number" name="required_experience" required>
    <br><br>
    <button type="submit">Add</button>
  </form>

  <h2>Job List</h2>
  <table id="job-table">
    <thead>
      <tr>
        <th>ID</th>
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

  <script>
    $(document).ready(function() {
      // Mengambil data pekerjaan dari REST API dan memperbarui tabel
      function getJobs() {
        $.ajax({
          url: 'http://localhost:3000/jobs',
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            displayJobs(data);
          },
          error: function() {
            alert('Failed to fetch job data.');
          }
        });
      }

      // Fungsi untuk menampilkan pekerjaan pada tabel
      function displayJobs(jobs) {
        var tableBody = $('#job-table tbody');
        tableBody.empty();
        jobs.forEach(function(job) {
          var row = '<tr>' +
            '<td>' + job.id + '</td>' +
            '<td>' + job.job_title + '</td>' +
            '<td>' + job.location + '</td>' +
            '<td>' + job.brief_description + '</td>' +
            '<td>' + job.detailed_description + '</td>' +
            '<td>' + job.required_experience + '</td>' +
            '<td>' +
            '<button class="edit-button" data-id="' + job.id + '">Edit</button>' +
            '<button class="delete-button" data-id="' + job.id + '">Delete</button>' +
            '</td>' +
            '</tr>';
          tableBody.append(row);
        });
      }

      // Validasi form menggunakan jQuery Validation
      $('#add-job-form').validate();

      // Tambahkan job baru melalui form
      $('#add-job-form').submit(function(event) {
        event.preventDefault();
        if ($(this).valid()) {
          var jobData = {
            job_title: $('input[name="job_title"]').val(),
            location: $('input[name="location"]').val(),
            brief_description: $('textarea[name="brief_description"]').val(),
            detailed_description: $('textarea[name="detailed_description"]').val(),
            required_experience: parseInt($('input[name="required_experience"]').val())
          };

          $.ajax({
            url: 'http://localhost:3000/jobs',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(jobData),
            success: function() {
              $('#add-job-form')[0].reset();
              getJobs();
            },
            error: function() {
              alert('Failed to add job.');
            }
          });
        }
      });

      // Menghapus job
      $('#job-table').on('click', '.delete-button', function() {
        var jobId = $(this).data('id');
        $.ajax({
          url: 'http://localhost:3000/jobs/' + jobId,
          type: 'DELETE',
          success: function() {
            getJobs();
          },
          error: function() {
            alert('Failed to delete job.');
          }
        });
      });

      // Menampilkan form update job
      $('#job-table').on('click', '.edit-button', function() {
        var jobId = $(this).data('id');
        $.ajax({
          url: 'http://localhost:3000/jobs/' + jobId,
          type: 'GET',
          dataType: 'json',
          success: function(job) {
            $('#add-job-form').attr('action', 'http://localhost:3000/jobs/' + jobId);
            $('input[name="job_title"]').val(job.job_title);
            $('input[name="location"]').val(job.location);
            $('textarea[name="brief_description"]').val(job.brief_description);
            $('textarea[name="detailed_description"]').val(job.detailed_description);
            $('input[name="required_experience"]').val(job.required_experience);
          },
          error: function() {
            alert('Failed to fetch job data for editing.');
          }
        });
      });

      // Update job melalui form
      $('#add-job-form').on('submit', function(event) {
        event.preventDefault();
        var jobId = $(this).attr('action').split('/').pop();
        if ($(this).valid()) {
          var jobData = {
            job_title: $('input[name="job_title"]').val(),
            location: $('input[name="location"]').val(),
            brief_description: $('textarea[name="brief_description"]').val(),
            detailed_description: $('textarea[name="detailed_description"]').val(),
            required_experience: parseInt($('input[name="required_experience"]').val())
          };

          $.ajax({
            url: 'http://localhost:3000/jobs/' + jobId,
            type: 'PUT',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(jobData),
            success: function() {
              $('#add-job-form').attr('action', '');
              $('#add-job-form')[0].reset();
              getJobs();
            },
            error: function() {
              alert('Failed to update job.');
            }
          });
        }
      });

      // Fungsi untuk melakukan pencarian pekerjaan
      function searchJobs(keyword) {
        $.ajax({
          url: 'http://localhost:3000/jobs?q=' + keyword,
          type: 'GET',
          dataType: 'json',
          success: function(jobs) {
            displayJobs(jobs);
          },
          error: function() {
            alert('Failed to search for jobs.');
          }
        });
      }

      // Menggunakan event submit pada form pencarian
      $('#search-form').submit(function(event) {
        event.preventDefault();
        var keyword = $('#search-input').val();
        searchJobs(keyword);
      });

      // Menggunakan event keyup pada input pencarian
      $('#search-input').keyup(function() {
        var keyword = $(this).val();
        searchJobs(keyword);
      });

      // Memuat data pekerjaan saat halaman dimuat
      getJobs();
    });
  </script>
</body>
</html>
