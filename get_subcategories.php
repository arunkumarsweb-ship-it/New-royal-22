<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

if ($category_id <= 0) {
    echo json_encode(['error' => 'Invalid category ID']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, category_id, day_number FROM subcategories WHERE category_id = ? ORDER BY day_number");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

$subcategories = [];
while ($row = $result->fetch_assoc()) {
    $subcategories[] = $row;
}

$stmt->close();
closeDBConnection($conn);
echo json_encode($subcategories);
?>

