<?php
// filepath: c:\xampp\htdocs\general\class\Car.php
class Car {
    private $conn;
    private $table = "vehicles";

    public $vehicle_type;
    public $license_plate;
    public $latest_mileage;
    public $fuel_level;
    public $image_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addCar() {
        $query = "INSERT INTO " . $this->table . " (vehicle_type, license_plate, latest_mileage, fuel_level, image_url)
                  VALUES (:vehicle_type, :license_plate, :latest_mileage, :fuel_level, :image_url)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':vehicle_type', $this->vehicle_type);
        $stmt->bindParam(':license_plate', $this->license_plate);
        $stmt->bindParam(':latest_mileage', $this->latest_mileage);
        $stmt->bindParam(':fuel_level', $this->fuel_level);
        $stmt->bindParam(':image_url', $this->image_url);

        return $stmt->execute();
    }

        // ฟังก์ชันสำหรับดึงข้อมูลรถทั้งหมด
    public function fetchVehicles() {
        $sql = "SELECT * FROM " . $this->table ;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCarById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCar() {
        $query = "UPDATE " . $this->table . " 
                SET vehicle_type = :vehicle_type, license_plate = :license_plate, 
                    latest_mileage = :latest_mileage, fuel_level = :fuel_level, 
                    image_url = COALESCE(:image_url, image_url)
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':vehicle_type', $this->vehicle_type);
        $stmt->bindParam(':license_plate', $this->license_plate);
        $stmt->bindParam(':latest_mileage', $this->latest_mileage);
        $stmt->bindParam(':fuel_level', $this->fuel_level);
        $stmt->bindParam(':image_url', $this->image_url);
        return $stmt->execute();
    }

    public function deleteCar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

}
?>