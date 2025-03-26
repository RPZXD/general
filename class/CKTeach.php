
<?php

class Subject {
    private $pdo;
    private $table = 'tb_subject';
    private $table_report = 'tb_ckteach';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch subjects by Teach_id
    public function getSubjectsByTeachId($teachId) {
        $sql = "SELECT * FROM {$this->table} 
        WHERE Teach_id = :teach_id
        AND table_status = 1
        ORDER BY sub_no DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':teach_id', $teachId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportByTeachId($teachId) {
        $sql = "SELECT t1.*, t2.sub_name
                FROM {$this->table_report} as t1
                INNER JOIN {$this->table} as t2 
                ON t1.sub_no = t2.sub_no
                WHERE t1.Teach_id = :teach_id
                ORDER BY t1.ck_date DESC, t1.ck_id DESC
                LIMIT 25";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':teach_id', $teachId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportTermPee($teachId, $term, $pee) {
        $sql = "SELECT * FROM {$this->table_report} as t1
                INNER JOIN {$this->table} as t2 
                ON t1.sub_no = t2.sub_no
                WHERE t1.Teach_id = :teach_id
                AND t1.ck_term = :term
                AND t1.ck_pee = :pee
                ORDER BY t1.ck_date DESC, t1.ck_id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':teach_id', $teachId, PDO::PARAM_INT);
        $stmt->bindParam(':term', $term, PDO::PARAM_INT);
        $stmt->bindParam(':pee', $pee, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjectsById($id) {
        $sql = "SELECT * FROM {$this->table} 
        WHERE sub_no = :sub_no
        AND table_status = 1
        ORDER BY sub_no DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':sub_no', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportById($id) {
        $sql = "SELECT t1.* , t2.sub_id, t2.sub_name
        FROM {$this->table_report} as t1
        INNER JOIN {$this->table} as t2
        ON t1.sub_no = t2.sub_no 
        WHERE t1.id = :report_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':report_id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll
    }

    public function getReportByIdforPrint($id) {
        $sql = "SELECT t1.* , t2.sub_id, t2.sub_name
        FROM {$this->table_report} as t1
        INNER JOIN {$this->table} as t2
        ON t1.sub_no = t2.sub_no 
        WHERE t1.id = :report_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':report_id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll
    }
    

    public function insertSubject($sub_name, $sub_id, $sub_level, $sub_type, $sub_status, $teach_id, $department) {
        $query = "INSERT INTO tb_subject (sub_name, sub_id, sub_level, tsub_id, sub_status, Teach_id, department) VALUES (:sub_name, :sub_id, :sub_level, :sub_type, :sub_status, :teach_id, :department)";
    
        $stmt = $this->pdo->prepare($query);
    
        $stmt->bindParam(':sub_name', $sub_name);
        $stmt->bindParam(':sub_id', $sub_id);
        $stmt->bindParam(':sub_level', $sub_level);
        $stmt->bindParam(':sub_type', $sub_type);
        $stmt->bindParam(':sub_status', $sub_status);
        $stmt->bindParam(':teach_id', $teach_id);
        $stmt->bindParam(':department', $department);
    
        return $stmt->execute();
    }

    // Method to insert a report
    public function insertReport($ck_id, $teach_id, $ck_date, $sub_no, $ck_level, $ck_room, $ck_pstart, $ck_pend, $ck_plan, $ck_title, $ck_event, $ck_attend, $ck_rec_K, $ck_rec_P, $ck_rec_A, $ck_problem, $ck_solution, $img_name, $ck_term, $ck_pee) {
        $query = "INSERT INTO tb_ckteach (ck_id, teach_id, ck_date, sub_no, ck_level, ck_room, ck_pstart, ck_pend, ck_plan, ck_title, ck_event, ck_attend, ck_rec_K, ck_rec_P, ck_rec_A, ck_problem, ck_solution, img_name, ck_term, ck_pee, start_created) 
                  VALUES (:ck_id, :teach_id, :ck_date, :sub_no, :ck_level, :ck_room, :ck_pstart, :ck_pend, :ck_plan, :ck_title, :ck_event, :ck_attend, :ck_rec_K, :ck_rec_P, :ck_rec_A, :ck_problem, :ck_solution, :img_name, :ck_term, :ck_pee, NOW())";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':ck_id', $ck_id);
        $stmt->bindParam(':teach_id', $teach_id);
        $stmt->bindParam(':ck_date', $ck_date);
        $stmt->bindParam(':sub_no', $sub_no, PDO::PARAM_INT);
        $stmt->bindParam(':ck_level', $ck_level, PDO::PARAM_INT);
        $stmt->bindParam(':ck_room', $ck_room, PDO::PARAM_INT);
        $stmt->bindParam(':ck_pstart', $ck_pstart, PDO::PARAM_INT);
        $stmt->bindParam(':ck_pend', $ck_pend, PDO::PARAM_INT);
        $stmt->bindParam(':ck_plan', $ck_plan, PDO::PARAM_INT);
        $stmt->bindParam(':ck_title', $ck_title);
        $stmt->bindParam(':ck_event', $ck_event);
        $stmt->bindParam(':ck_attend', $ck_attend);
        $stmt->bindParam(':ck_rec_K', $ck_rec_K);
        $stmt->bindParam(':ck_rec_P', $ck_rec_P);
        $stmt->bindParam(':ck_rec_A', $ck_rec_A);
        $stmt->bindParam(':ck_problem', $ck_problem);
        $stmt->bindParam(':ck_solution', $ck_solution);
        $stmt->bindParam(':img_name', $img_name);
        $stmt->bindParam(':ck_term', $ck_term, PDO::PARAM_INT);
        $stmt->bindParam(':ck_pee', $ck_pee, PDO::PARAM_INT);

        return $stmt->execute();
    }


    // Update a subject by sub_id
    public function updateSubject($sub_no, $tsub_id, $sub_name, $sub_id, $sub_status, $sub_level) {
        $sql = "UPDATE {$this->table} SET tsub_id = :tsub_id, sub_name = :sub_name, 
                sub_status = :sub_status, sub_level = :sub_level, sub_id = :sub_id
                WHERE sub_no = :sub_no";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':sub_no', $sub_no);
        $stmt->bindParam(':tsub_id', $tsub_id);
        $stmt->bindParam(':sub_name', $sub_name);
        $stmt->bindParam(':sub_id', $sub_id);
        $stmt->bindParam(':sub_status', $sub_status);
        $stmt->bindParam(':sub_level', $sub_level);
        return $stmt->execute();
    }
    public function updateReport($editReportid, $ck_date, $sub_no, $ck_level, $ck_room, $ck_pstart, $ck_pend, $ck_plan, $ck_title, $ck_event, $ck_attend, $ck_rec_K, $ck_rec_P, $ck_rec_A, $ck_problem, $ck_solution, $ck_term, $ck_pee, $img_name) {
        $sql = "UPDATE {$this->table_report} SET 
                    ck_date = :ck_date,
                    sub_no = :sub_no,
                    ck_level = :ck_level,
                    ck_room = :ck_room,
                    ck_pstart = :ck_pstart,
                    ck_pend = :ck_pend,
                    ck_plan = :ck_plan,
                    ck_title = :ck_title,
                    ck_event = :ck_event,
                    ck_attend = :ck_attend,
                    ck_rec_K = :ck_rec_K,
                    ck_rec_P = :ck_rec_P,
                    ck_rec_A = :ck_rec_A,
                    ck_problem = :ck_problem,
                    ck_solution = :ck_solution,
                    ck_term = :ck_term,
                    ck_pee = :ck_pee";
        
        if ($img_name !== null) {
            $sql .= ", img_name = :img_name";
        }
    
        $sql .= " WHERE id = :editReportid";
    
        $stmt = $this->pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':editReportid', $editReportid);
        $stmt->bindParam(':ck_date', $ck_date);
        $stmt->bindParam(':sub_no', $sub_no);
        $stmt->bindParam(':ck_level', $ck_level);
        $stmt->bindParam(':ck_room', $ck_room);
        $stmt->bindParam(':ck_pstart', $ck_pstart);
        $stmt->bindParam(':ck_pend', $ck_pend);
        $stmt->bindParam(':ck_plan', $ck_plan);
        $stmt->bindParam(':ck_title', $ck_title);
        $stmt->bindParam(':ck_event', $ck_event);
        $stmt->bindParam(':ck_attend', $ck_attend);
        $stmt->bindParam(':ck_rec_K', $ck_rec_K);
        $stmt->bindParam(':ck_rec_P', $ck_rec_P);
        $stmt->bindParam(':ck_rec_A', $ck_rec_A);
        $stmt->bindParam(':ck_problem', $ck_problem);
        $stmt->bindParam(':ck_solution', $ck_solution);
        $stmt->bindParam(':ck_term', $ck_term);
        $stmt->bindParam(':ck_pee', $ck_pee);
        
        if ($img_name !== null) {
            $stmt->bindParam(':img_name', $img_name);
        }
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    

    // Delete a subject by sub_id
    public function deleteSubject($sub_no) {
        $sql = "UPDATE {$this->table} 
        SET table_status = 0
        WHERE sub_no = :sub_no";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':sub_no', $sub_no);
        return $stmt->execute();
    }

    // Delete a report by id
    public function deleteReport($ck_id) {
        $sql = "DELETE FROM {$this->table_report} WHERE id = :ck_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':ck_id', $ck_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}


?>

