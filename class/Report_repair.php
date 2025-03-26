<?php

class Report_repair {
    private $pdo;
    private $table = 'report_repair';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getReportByTeachId($teachId) {
        $sql = "SELECT * FROM {$this->table} 
        WHERE teach_id = :teach_id
        ORDER BY id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':teach_id', $teachId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReport() {
        $sql = "SELECT * FROM {$this->table} 
        ORDER BY id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':teach_id', $teachId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a report by id
    public function deleteReport($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getStatus($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT status FROM report_repair WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result['status'];
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }

    public function getReportById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Add a new method to count the total number of reports
    public function countReports($status) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
