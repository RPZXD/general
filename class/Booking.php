<?php
class Booking
{
    private $pdo;
    private $table = "bookings";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a booking
    public function createBooking($data)
    {
        $query = "INSERT INTO " . $this->table . " 
            (teach_id, term, pee, date, time_start, time_end, purpose, location, media, phone) 
            VALUES 
            (:teach_id, :term, :pee, :date, :time_start, :time_end, :purpose, :location, :media, :phone)";

        $stmt = $this->pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(':teach_id', $data['teach_id']);
        $stmt->bindParam(':term', $data['term']);
        $stmt->bindParam(':pee', $data['pee']);
        $stmt->bindParam(':date', $data['date']);
        $stmt->bindParam(':time_start', $data['time_start']);
        $stmt->bindParam(':time_end', $data['time_end']);
        $stmt->bindParam(':purpose', $data['purpose']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':media', $data['media']);
        $stmt->bindParam(':phone', $data['phone']);

        return $stmt->execute();
    }

    // Check for booking conflicts
    public function checkConflict($location, $date, $startTime, $endTime) {
        $query = "SELECT COUNT(*) FROM bookings WHERE location = :location AND date = :date 
                  AND ((time_start <= :endTime AND time_end >= :startTime))"; // Check if time conflicts
        $stmt = $this->pdo->prepare($query);
    
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':startTime', $startTime);
        $stmt->bindParam(':endTime', $endTime);
    
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        return $count > 0;
    }
    
    // Delete a booking
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get all bookings
    public function getBooking() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count bookings by status
    public function countBookings($status) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
