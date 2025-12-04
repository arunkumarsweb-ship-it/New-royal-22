<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
}

$conn = getDBConnection();

// Get image path before deleting
$stmt = $conn->prepare("SELECT image_path FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['error' => 'Product not found']);
    closeDBConnection($conn);
    exit;
}

// Delete product from database
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    // Delete image file if exists
    if ($product['image_path'] && file_exists('../' . $product['image_path'])) {
        unlink('../' . $product['image_path']);
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to delete product: ' . $conn->error]);
}

$stmt->close();
closeDBConnection($conn);
?>

