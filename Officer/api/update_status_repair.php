<?php
header('Content-Type: application/json');

// Include your database configuration
require_once '../../config/Database.php'; // Adjust the path as needed

// Create a new database connection instance
$database = new Database("phichaia_general");
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the ID and status from POST data
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $status = isset($_POST['status']) ? intval($_POST['status']) : null;

    // Validate the input
    if ($id === null || $status === null) {
        echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
        exit;
    }

    try {
        // Prepare the SQL query
        $query = "UPDATE report_repair SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตสถานะได้']);
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo json_encode(['success' => false, 'message' => 'ข้อผิดพลาด: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'คำขอไม่ถูกต้อง']);
}
?>
