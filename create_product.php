<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$subcategory_id = isset($_POST['subcategory_id']) ? (int)$_POST['subcategory_id'] : 0;
$product_number = isset($_POST['product_number']) ? (int)$_POST['product_number'] : 0;
$availability_status = isset($_POST['availability_status']) ? $_POST['availability_status'] : 'available';

if ($subcategory_id <= 0 || $product_number <= 0) {
    echo json_encode(['error' => 'Invalid subcategory ID or product number']);
    exit;
}

if (!in_array($availability_status, ['available', 'unavailable', 'hold'])) {
    $availability_status = 'available';
}

// Handle image upload
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/products/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    
    if (in_array($file_extension, $allowed_extensions)) {
        $filename = 'product_' . $subcategory_id . '_' . $product_number . '_' . time() . '.' . $file_extension;
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = 'uploads/products/' . $filename;
        } else {
            echo json_encode(['error' => 'Failed to upload image']);
            exit;
        }
    } else {
        echo json_encode(['error' => 'Invalid file type']);
        exit;
    }
} else {
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("INSERT INTO products (subcategory_id, product_number, image_path, availability_status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $subcategory_id, $product_number, $image_path, $availability_status);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
} else {
    echo json_encode(['error' => 'Failed to create product: ' . $conn->error]);
}

$stmt->close();
closeDBConnection($conn);
?>

