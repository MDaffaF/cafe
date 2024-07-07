<?php
$conn = mysqli_connect("localhost", "root", "", "cafe");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
