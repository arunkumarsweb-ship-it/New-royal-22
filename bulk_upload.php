<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$subcategory_id = isset($_POST['subcategory_id']) ? (int)$_POST['subcategory_id'] : 0;
$starting_number = isset($_POST['starting_number']) ? (int)$_POST['starting_number'] : 1;
$numbering_mode = isset($_POST['numbering_mode']) ? $_POST['numbering_mode'] : 'auto'; // 'auto' or 'manual'
$availability_status = isset($_POST['availability_status']) ? $_POST['availability_status'] : 'available';

if ($subcategory_id <= 0) {
    echo json_encode(['error' => 'Invalid subcategory ID']);
    exit;
}

if (!in_array($availability_status, ['available', 'unavailable', 'hold'])) {
    $availability_status = 'available';
}

if (!isset($_FILES['images']) || !is_array($_FILES['images']['name'])) {
    echo json_encode(['error' => 'No images uploaded']);
    exit;
}

$upload_dir = '../uploads/products/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$uploaded_count = 0;
$errors = [];
$conn = getDBConnection();

// Get current max product number for this subcategory
$max_stmt = $conn->prepare("SELECT MAX(product_number) as max_num FROM products WHERE subcategory_id = ?");
$max_stmt->bind_param("i", $subcategory_id);
$max_stmt->execute();
$max_result = $max_stmt->get_result();
$max_row = $max_result->fetch_assoc();
$current_max = $max_row['max_num'] ? $max_row['max_num'] : 0;
$max_stmt->close();

$product_number = ($numbering_mode === 'auto') ? max($current_max + 1, $starting_number) : $starting_number;

$insert_stmt = $conn->prepare("INSERT INTO products (subcategory_id, product_number, image_path, availability_status) VALUES (?, ?, ?, ?)");

foreach ($_FILES['images']['name'] as $key => $filename) {
    if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading file: " . $filename;
        continue;
    }
    
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_extensions)) {
        $errors[] = "Invalid file type: " . $filename;
        continue;
    }
    
    $unique_filename = 'product_' . $subcategory_id . '_' . $product_number . '_' . time() . '_' . $key . '.' . $file_extension;
    $target_path = $upload_dir . $unique_filename;
    
    if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_path)) {
        $image_path = 'uploads/products/' . $unique_filename;
        
        $insert_stmt->bind_param("iiss", $subcategory_id, $product_number, $image_path, $availability_status);
        
        if ($insert_stmt->execute()) {
            $uploaded_count++;
            // Increment product number for next image
            // In auto mode: continues from max or starting number
            // In manual mode: starts from specified starting number and increments
            $product_number++;
        } else {
            unlink($target_path); // Delete uploaded file if DB insert fails
            $errors[] = "Failed to save product: " . $filename;
        }
    } else {
        $errors[] = "Failed to upload file: " . $filename;
    }
}

$insert_stmt->close();
closeDBConnection($conn);

if ($uploaded_count > 0) {
    echo json_encode([
        'success' => true,
        'uploaded_count' => $uploaded_count,
        'errors' => $errors
    ]);
} else {
    echo json_encode([
        'error' => 'No files were uploaded successfully',
        'errors' => $errors
    ]);
}
?>

