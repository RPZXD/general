<?php

include_once("../../config/Database.php");
include_once("../../class/Setting.php");

$database = new Database_General();
$db = $database->getConnection();

$config = new Setting_Config($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $room_name = $_POST['room_name'] ?? null;
    $capacity = $_POST['capacity'] ?? null;
    $equipment = $_POST['equipment'] ?? null;

    $result = $config->updateMeetingRoom($id, $room_name, $capacity, $equipment);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตรายละเอียดรางวัลได้']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'วิธีการร้องขอไม่ถูกต้อง']);
}
?>

