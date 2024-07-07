<?php

$db = mysqli_connect("localhost:3306", "root", "");
mysqli_select_db($db, "licente");

$updatedName = $_POST['name'];
$updatedEmail = $_POST['email'];
$updatedData = $_POST['data'];
$updateLicenta = $_POST['lic'];

$id_inregistrare = $_GET['id'];


$stmt = $db->prepare("UPDATE element SET nume = ?, email = ?, data_ = ?, nr_licenta =? WHERE id = ?");
$stmt->bind_param("ssssi", $updatedName, $updatedEmail, $updatedData, $updateLicenta, $id_inregistrare);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Updated successfully.";
} else {
    echo "Error updating.";
}

$stmt->close();

?>