<?php

class User {
    private $conn;
    private $table_name = "teacher";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function userDepartment($department) {
        try {
            $query = "SELECT Teach_id, Teach_name 
            FROM {$this->table_name} 
            WHERE Teach_major = :department
            AND Teach_status = 1
            ORDER BY Teach_id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":department", $department);
            $stmt->execute();
            
            // Fetch all matching records
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return results if found, otherwise return false
            return $stmt->rowCount() > 0 ? $results : false;
        } catch (PDOException $e) {
            // Log error or handle accordingly
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public function userTeacher() {
        try {
            $query = "SELECT * 
            FROM {$this->table_name} 
            ORDER BY Teach_id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // Fetch all matching records
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return results if found, otherwise return false
            return $stmt->rowCount() > 0 ? $results : false;
        } catch (PDOException $e) {
            // Log error or handle accordingly
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public function getTeacherById($teacher_id) {
        try {
            $query = "SELECT * 
            FROM {$this->table_name} 
            WHERE Teach_id = :teacher_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":teacher_id", $teacher_id);
            $stmt->execute();
            
            // Fetch all matching records
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return results if found, otherwise return false
            return $stmt->rowCount() > 0 ? $results : false;
        } catch (PDOException $e) {
            // Log error or handle accordingly
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public function getDepartment() {
        try {
            $query = "SELECT DISTINCT Teach_major FROM {$this->table_name}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // Fetch all matching records
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Return results if found, otherwise return false
            return $stmt->rowCount() > 0 ? $results : false;
        } catch (PDOException $e) {
            // Log error or handle accordingly
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    

    public function updateTeacher($update_id, $Teach_id, $Teach_password, $Teach_name, $Teach_major, $Teach_class, $Teach_room, $Teach_status, $role_ckteach) {
        $sql = "UPDATE {$this->table_name} SET 
                    Teach_id = :teachid,
                    Teach_password = :Teach_password,
                    Teach_name = :Teach_name,
                    Teach_major = :Teach_major,
                    Teach_class = :Teach_class,
                    Teach_room = :Teach_room,
                    Teach_status = :Teach_status,
                    role_ckteach = :role_ckteach
                WHERE Teach_id = :teacher_id";
    
        $stmt = $this->conn->prepare($sql);
    
        // Bind parameters
        $stmt->bindParam(':teachid', $Teach_id);
        $stmt->bindParam(':Teach_password', $Teach_password);
        $stmt->bindParam(':Teach_name', $Teach_name);
        $stmt->bindParam(':Teach_major', $Teach_major);
        $stmt->bindParam(':Teach_class', $Teach_class);
        $stmt->bindParam(':Teach_room', $Teach_room);
        $stmt->bindParam(':Teach_status', $Teach_status);
        $stmt->bindParam(':role_ckteach', $role_ckteach);
        $stmt->bindParam(':teacher_id', $update_id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    
    

}
?>
