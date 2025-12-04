<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$conn = getDBConnection();
$result = $conn->query("SELECT id, name FROM categories ORDER BY id");

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

closeDBConnection($conn);
echo json_encode($categories);
?>

