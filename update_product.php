<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$product_number = isset($_POST['product_number']) ? (int)$_POST['product_number'] : null;
$availability_status = isset($_POST['availability_status']) ? $_POST['availability_status'] : null;

if ($product_id <= 0) {
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
}

$conn = getDBConnection();

// Get current product data
$stmt = $conn->prepare("SELECT image_path, subcategory_id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$current_product = $result->fetch_assoc();
$stmt->close();

if (!$current_product) {
    echo json_encode(['error' => 'Product not found']);
    closeDBConnection($conn);
    exit;
}

$image_path = $current_product['image_path'];
$subcategory_id = $current_product['subcategory_id'];

// Handle image upload if new image is provided
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/products/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    
    if (in_array($file_extension, $allowed_extensions)) {
        // Delete old image if exists
        if ($image_path && file_exists('../' . $image_path)) {
            unlink('../' . $image_path);
        }
        
        $product_num = $product_number ? $product_number : $subcategory_id;
        $filename = 'product_' . $subcategory_id . '_' . $product_num . '_' . time() . '.' . $file_extension;
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = 'uploads/products/' . $filename;
        }
    }
}

// Build update query dynamically
$updates = [];
$params = [];
$types = '';

if ($product_number !== null) {
    $updates[] = "product_number = ?";
    $params[] = $product_number;
    $types .= 'i';
}

if ($availability_status !== null && in_array($availability_status, ['available', 'unavailable', 'hold'])) {
    $updates[] = "availability_status = ?";
    $params[] = $availability_status;
    $types .= 's';
}

if ($image_path !== $current_product['image_path']) {
    $updates[] = "image_path = ?";
    $params[] = $image_path;
    $types .= 's';
}

if (empty($updates)) {
    echo json_encode(['error' => 'No fields to update']);
    closeDBConnection($conn);
    exit;
}

$params[] = $product_id;
$types .= 'i';

$sql = "UPDATE products SET " . implode(', ', $updates) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update product: ' . $conn->error]);
}

$stmt->close();
closeDBConnection($conn);
?>

