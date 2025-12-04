<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$subcategory_id = isset($_GET['subcategory_id']) ? (int)$_GET['subcategory_id'] : 0;

if ($subcategory_id <= 0) {
    echo json_encode(['error' => 'Invalid subcategory ID']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, subcategory_id, product_number, image_path, availability_status FROM products WHERE subcategory_id = ? ORDER BY product_number");
$stmt->bind_param("i", $subcategory_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
closeDBConnection($conn);
echo json_encode($products);
?>

