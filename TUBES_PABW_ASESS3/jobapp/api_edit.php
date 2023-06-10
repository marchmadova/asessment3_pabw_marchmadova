<?php
require_once('../config/koneksi_db.php');
$data = json_decode(file_get_contents("php://input"));

if ($data->id != null) {
    $id = $data->id;
    $job_title = $data->job_title;
    $location = $data->location;
    $brief_description = $data->brief_description;
    $detailed_description = $data->detailed_description;
    $required_experience = $data->required_experience;

    $sql = $conn->prepare("UPDATE jobs SET job_title=?, location=?, brief_description=?, detailed_description=?, required_experience=? WHERE id=?");
    $sql->bind_param('sssssi', $job_title, $location, $brief_description, $detailed_description, $required_experience, $id);
    $sql->execute();

    if ($sql) {
        echo json_encode(array('RESPONSE' => 'SUCCESS'));
    } else {
        echo json_encode(array('RESPONSE' => 'FAILED'));
    }
} else {
    echo "GAGAL";
}
?>
