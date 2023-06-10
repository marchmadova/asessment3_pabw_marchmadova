
<?php
require_once('../config/koneksi_db.php');
$myArray = array();

if(isset($_GET['id'])){
    $id = $_GET['id'];

    if ($result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = '$id' ORDER BY id ASC")) {
        while ($row = $result->fetch_assoc()) {
            $myArray[] = $row;
        }
        mysqli_free_result($result);
        mysqli_close($conn);
        echo json_encode($myArray);
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
} else {
    echo "Parameter 'id' tidak ditemukan dalam URL.";
}
?>
