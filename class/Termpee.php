
<?php

class School_Year {
    private $conn;
    private $table = 'termpee';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch subjects by Teach_id
    public function gettermpee() {
        $sql = "SELECT * FROM {$this->table} ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>

