<?php

$db = mysqli_connect("localhost:3306", "root", "");
mysqli_select_db($db, "licente");

$id_inregistrare = $_GET['id'];

$sql = "DELETE FROM element WHERE id = $id_inregistrare";
    if ($db->query($sql) === TRUE) {
        echo "Registration deleted successfully.";
    } else {
        echo "Error deleting registration: " . $db->error;
    }
    
?>