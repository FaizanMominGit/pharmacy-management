<?php
$conn = mysqli_connect("localhost", "root", "", "healthcare");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
