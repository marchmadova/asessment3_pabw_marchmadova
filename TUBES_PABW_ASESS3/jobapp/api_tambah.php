

<?php
require_once('../config/koneksi_db.php');

if (isset($_POST['job_title']) && isset($_POST['location']) && isset($_POST['brief_description']) && isset($_POST['detailed_description']) && isset($_POST['required_experience'])) {
    $job_title = $_POST['job_title'];
    $location = $_POST['location'];
    $brief_description = $_POST['brief_description'];
    $detailed_description = $_POST['detailed_description'];
    $required_experience = $_POST['required_experience'];

    $sql = $conn->prepare("INSERT INTO jobs (job_title, location, brief_description, detailed_description, required_experience) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param('sssss', $job_title, $location, $brief_description, $detailed_description, $required_experience);
    $sql->execute();

    if ($sql) {
        echo json_encode(array('RESPONSE' => 'SUCCESS'));
    } else {
        echo json_encode(array('RESPONSE' => 'FAILED'));
    }
} else {
    echo json_encode(array('RESPONSE' => 'MISSING PARAMETERS'));
}
?>
