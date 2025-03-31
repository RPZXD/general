<?php

require_once '../../config/Database.php';
require_once '../../class/Driver.php';


$databaseGeneral = new Database("phichaia_general");
$dbGeneral = $databaseGeneral->getConnection();




$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['status'])) {
    $sql = "UPDATE drivers SET status = :status WHERE id = :id";
    $stmt = $dbGeneral->prepare($sql);
    $stmt->bindParam(':status', $data['status']);
    $stmt->bindParam(':id', $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "อัปเดตสถานะสำเร็จ"]);
    } else {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาด"]);
    }
}
?>
