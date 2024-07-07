<?php

$db = mysqli_connect("localhost:3306", "root", "");
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8");
mysqli_select_db($db, "licente");

$name = $_POST['name'];
$email = $_POST['email'];
$data = $_POST['data'];
$lic = $_POST['lic'];

$stmt = $db->prepare("INSERT INTO element(nume, email, data_, nr_licenta) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $data, $lic);
$result = $stmt->execute();

if ($result) {
    echo "Item added successfully";
} else {
    $error = mysqli_error($db);
    http_response_code(500);
    echo $error;
}

?>