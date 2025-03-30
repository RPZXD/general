<?php

include_once("../../config/Database.php");
include_once("../../class/Setting.php");

// Initialize database connection
$connectDB = new Database("phichaia_general");
$db = $connectDB->getConnection();

// Initialize Setting class
$settingconfig = new Setting_Config($db);

// Get POST data
$room_name = $_POST['room_name'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$equipment = $_POST['equipment'] ?? '';

// Insert meeting room
try {
    if ($settingconfig->insertMeetingRoom($room_name, $capacity, $equipment)) {
        echo json_encode(['success' => true, 'message' => 'Meeting room added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add meeting room.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
