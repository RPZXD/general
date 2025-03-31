<?php
// filepath: c:\xampp\htdocs\general\class\Car.php
class Driver {
    private $conn;
    private $table = "drivers";

    public $full_name;
    public $phone;
    public $license_no;
    public $license_expiry;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addDriver() {
        $query = "INSERT INTO " . $this->table . " (full_name, phone, license_no, license_expiry, status, created_at)
                  VALUES (:full_name, :phone, :license_no, :license_expiry, 'active', NOW())";

        $stmt = $this->conn->prepare($query);

        // Bind Parameters
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':license_no', $this->license_no);
        $stmt->bindParam(':license_expiry', $this->license_expiry);

        return $stmt->execute();
    }


        // ฟังก์ชันสำหรับดึงข้อมูลรถทั้งหมด
    public function fetchDrivers() {
        $sql = "SELECT * FROM " . $this->table ;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getDriverById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateDriver() {
        $query = "UPDATE " . $this->table . " 
                SET full_name = :full_name, phone = :phone, 
                    license_no = :license_no, license_expiry = :license_expiry
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':license_no', $this->license_no);
        $stmt->bindParam(':license_expiry', $this->license_expiry);
        return $stmt->execute();
    }

    public function deleteDriver($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

}
?>