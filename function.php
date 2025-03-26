<?php
function DateThai2($strDate)
{
    // Set the time zone to Thailand
    $timeZone = new DateTimeZone('Asia/Bangkok');

    // Create a new DateTime object with the provided date in the default time zone
    $date = new DateTime($strDate);

    // Set the time zone for the DateTime object to Thailand
    $date->setTimezone($timeZone);

    $strYear = $date->format("Y") + 543;
    $strMonth = $date->format("n");
    $strDay = $date->format("j");
    $strWeekday = $date->format("N");

    $strWeekCut = Array("", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์", "อาทิตย์");
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");

    $strWeekThai = $strWeekCut[$strWeekday];
    $strMonthThai = $strMonthCut[$strMonth];

    return $strDay . "&nbsp;&nbsp;" . $strMonthThai . "&nbsp;&nbsp;" . $strYear;
}

function DateThai22($strDate)
{
    // Set the time zone to Thailand
    $timeZone = new DateTimeZone('Asia/Bangkok');

    // Create a new DateTime object with the provided date in the default time zone
    $date = new DateTime($strDate);

    // Set the time zone for the DateTime object to Thailand
    $date->setTimezone($timeZone);

    $strYear = $date->format("Y") + 543;
    $strMonth = $date->format("n");
    $strDay = $date->format("j");

    return $strYear . "-" . str_pad($strMonth, 2, '0', STR_PAD_LEFT) . "-" . str_pad($strDay, 2, '0', STR_PAD_LEFT);
}

function DateTh($strDate)
{
    // Set the time zone to Thailand
    $timeZone = new DateTimeZone('Asia/Bangkok');

    // Create a new DateTime object with the provided date in the default time zone
    $date = new DateTime($strDate);

    // Set the time zone for the DateTime object to Thailand
    $date->setTimezone($timeZone);

    $strYear = $date->format("Y") + 543;
    $strMonth = $date->format("n");
    $strDay = $date->format("j");

    return $strYear . "-" . str_pad($strMonth, 2, '0', STR_PAD_LEFT) . "-" . str_pad($strDay, 2, '0', STR_PAD_LEFT);
}

function getStCountAllinschool($db, $level) {
    if($level == 1) {
        $select_stmt = $db->prepare("SELECT Stu_id FROM student WHERE (Stu_major = 1 OR Stu_major = 2 OR Stu_major = 3 ) AND Stu_status = 1");
        $select_stmt->execute();
        $results = $select_stmt->fetchAll();
        $count = count($results);
        
        return $count;
    }

   else if ($level == 2) {
    $select_stmt = $db->prepare("SELECT Stu_id FROM student  WHERE (Stu_major = 4 OR Stu_major = 5 OR Stu_major = 6 ) AND Stu_status = 1");
    $select_stmt->execute();
    $results = $select_stmt->fetchAll();
    $count = count($results);
    
    return $count;
  }
}

function strstatusck($valck)
{
    switch ($valck) {
        case "1":
          $results = 'มาเรียน';
          break;
        case "2":
          $results = 'ขาดเรียน';
          break;
        case "3":
          $results = 'มาสาย';
          break;
        case "4":
          $results = 'ลาป่วย';
          break;
        case "5":
          $results = 'ลากิจ';
          break;
        case "6":
          $results = 'เข้าร่วมกิจกรรม';
          break;
        default:
          $results = '';
      }
    return $results;
}


function getStCountAll($db, $major, $room, $status) {
    $select_stmt = $db->prepare("SELECT COUNT(Stu_id) AS count FROM student WHERE Stu_major = :major AND Stu_room = :room AND Stu_status = :status");
    $select_stmt->execute([
        ':major' => $major,
        ':room' => $room,
        ':status' => $status
    ]);
    $result = $select_stmt->fetch();
    return $result['count'];
  }
  function getStCount($db, $date, $major, $room, $study_status) {
    $select_stmt = $db->prepare("SELECT COUNT(a.Stu_id) AS count 
      FROM study b 
      INNER JOIN student a ON a.Stu_id = b.Stu_id
      WHERE b.Study_date = :date 
      AND a.Stu_major = :major 
      AND a.Stu_room = :room 
      AND b.Study_status = :study_status 
      AND a.Stu_status = 1");
    $select_stmt->execute([
        ':date' => $date,
        ':major' => $major,
        ':room' => $room,
        ':study_status' => $study_status,
    ]);
    $result = $select_stmt->fetch();
    return $result['count'];
  }

  function getStCountComeAll($db, $date, $major, $room) {
    $select_stmt = $db->prepare("SELECT COUNT(a.Stu_id) AS count
      FROM study b 
      INNER JOIN student a ON a.Stu_id = b.Stu_id
      WHERE b.Study_date = :date 
      AND a.Stu_major = :major 
      AND a.Stu_room = :room 
      AND b.Study_status IN (1, 3, 6)
      AND a.Stu_status = 1");
    $select_stmt->execute([
        ':date' => $date,
        ':major' => $major,
        ':room' => $room,
    ]);
    $result = $select_stmt->fetch();
    return $result['count'];
  }

  function getStCountAbsent($db, $date, $major, $room) {
    $select_stmt = $db->prepare("SELECT COUNT(a.Stu_id) AS count
      FROM study b 
      INNER JOIN student a ON a.Stu_id = b.Stu_id
      WHERE b.Study_date = :date 
      AND a.Stu_major = :major 
      AND a.Stu_room = :room 
      AND b.Study_status IN (2, 4, 5)
      AND a.Stu_status = 1");
    $select_stmt->execute([
      ':date' => $date,
      ':major' => $major,
      ':room' => $room,
    ]);
    $result = $select_stmt->fetch();
    return $result['count'];
  }
  
  function getAttendanceData($db, $date) {
    $query = "
        SELECT
            a.Stu_major AS major,
            a.Stu_room AS room,
            COUNT(CASE WHEN a.Stu_status = 1 THEN 1 END) AS total,
            COUNT(CASE WHEN b.Study_status IN (1, 3, 6) AND a.Stu_status = 1 THEN 1 END) AS attended,
            COUNT(CASE WHEN b.Study_status = 2 AND a.Stu_status = 1 THEN 1 END) AS absent,
            COUNT(CASE WHEN b.Study_status = 3 AND a.Stu_status = 1 THEN 1 END) AS late,
            COUNT(CASE WHEN b.Study_status = 4 AND a.Stu_status = 1 THEN 1 END) AS sick_leave,
            COUNT(CASE WHEN b.Study_status = 5 AND a.Stu_status = 1 THEN 1 END) AS personal_leave,
            COUNT(CASE WHEN b.Study_status = 6 AND a.Stu_status = 1 THEN 1 END) AS official_leave
        FROM student a
        LEFT JOIN study b ON a.Stu_id = b.Stu_id AND b.Study_date = :date
        WHERE a.Stu_major IN (1, 2, 3) AND a.Stu_room BETWEEN 1 AND 12
        GROUP BY a.Stu_major, a.Stu_room
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':date' => $date]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAttendanceData2($db, $date) {
    $query = "
        SELECT
            a.Stu_major AS major,
            a.Stu_room AS room,
            COUNT(CASE WHEN a.Stu_status = 1 THEN 1 END) AS total,
            COUNT(CASE WHEN b.Study_status IN (1, 3, 6) AND a.Stu_status = 1 THEN 1 END) AS attended,
            COUNT(CASE WHEN b.Study_status = 2 AND a.Stu_status = 1 THEN 1 END) AS absent,
            COUNT(CASE WHEN b.Study_status = 3 AND a.Stu_status = 1 THEN 1 END) AS late,
            COUNT(CASE WHEN b.Study_status = 4 AND a.Stu_status = 1 THEN 1 END) AS sick_leave,
            COUNT(CASE WHEN b.Study_status = 5 AND a.Stu_status = 1 THEN 1 END) AS personal_leave,
            COUNT(CASE WHEN b.Study_status = 6 AND a.Stu_status = 1 THEN 1 END) AS official_leave
        FROM student a
        LEFT JOIN study b ON a.Stu_id = b.Stu_id AND b.Study_date = :date
        WHERE a.Stu_major IN (4, 5, 6) AND a.Stu_room BETWEEN 1 AND 7
        GROUP BY a.Stu_major, a.Stu_room
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':date' => $date]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function displaySuccessMessage($message, $page) {
  echo '
      <script>
            setTimeout(function() {
                Swal.fire({
                    title: "' . $message . '",
                    text: "",
                    icon: "success"
                }).then(function() {
                    window.location = "' . $page . '";
                });
            }, 2000);
      </script>
  ';
}


  
  function displayErrorMessage($message, $page) {
      echo '
      <script>
            setTimeout(function() {
                Swal.fire({
                    title: "' . $message . '",
                    text: "",
                    icon: "error"
                }).then(function() {
                    window.location = "' . $page . '";
                });
            }, 2000);
      </script>
  ';
  }
  

  
?>