<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "mmgroup";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select data from database
$sql = "SELECT id, name, mobilenumber FROM member";
$result = mysqli_query($conn, $sql);

// Put data in an array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Send data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close connection
mysqli_close($conn);
?>
