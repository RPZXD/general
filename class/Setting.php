<?php

class Setting_Config {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fetchMeetingRooms() {
        $query = "SELECT * FROM meeting_rooms";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertMeetingRoom($room_name, $capacity, $equipment) {
        $query = "INSERT INTO meeting_rooms (room_name, capacity, equipment) VALUES (:room_name, :capacity, :equipment)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_name', $room_name);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':equipment', $equipment);
        return $stmt->execute();
    }
}
?>
