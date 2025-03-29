<?php

include_once("../../config/Database.php");
include_once("../../class/Setting.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $connectDB = new Database_General();
    $db = $connectDB->getConnection();

    $query = "DELETE FROM meeting_rooms WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Meeting room deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete meeting room.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
