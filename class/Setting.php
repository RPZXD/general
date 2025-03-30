<?php

class Setting_Config {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fetchMeetingRooms() {
        try {
            $query = "SELECT * FROM meeting_rooms";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows
        } catch (PDOException $e) {
            error_log("Error fetching meeting rooms: " . $e->getMessage());
            return []; // Return an empty array on failure
        }
    }

    public function insertMeetingRoom($room_name, $capacity, $equipment) {
        $query = "INSERT INTO meeting_rooms (room_name, capacity, equipment) VALUES (:room_name, :capacity, :equipment)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_name', $room_name);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':equipment', $equipment);
        return $stmt->execute();
    }

    public function updateMeetingRoom($id, $room_name, $capacity, $equipment) {
        $query = "UPDATE meeting_rooms SET room_name = :room_name, capacity = :capacity, equipment = :equipment WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':room_name', $room_name);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':equipment', $equipment);
        return $stmt->execute();
    }
    public function deleteMeetingRoom($id) {
        $query = "DELETE FROM meeting_rooms WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }



    
}
?>
