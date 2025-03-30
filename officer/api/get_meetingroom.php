<?php

include_once("../../config/Database.php");
include_once("../../class/Setting.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $connectDB = new Database("phichaia_general");
    $db = $connectDB->getConnection();

    $setting = new Setting_Config($db);

    $query = "SELECT * FROM meeting_rooms WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Meeting room not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
