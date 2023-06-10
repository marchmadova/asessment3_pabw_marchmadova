<?php
require_once('../config/koneksi_db.php');
$data = json_decode(file_get_contents("php://input"));

if ($data ->id!=null) {
    $id=$data->id;
    $sql = $conn->prepare("DELETE FROM jobs WHERE id=?");
    $sql->bind_param('i', $id);
    $sql->execute();
    if ($sql) {
        echo json_encode(array('RESPONSE' => 'SUCCESS'));
        //header("location:../readapi/tampil.php");
    } else {
        echo json_encode(array('RESPONSE' => 'FAILED'));
    }
} else {
    echo "GAGAL";
}
