<?php

session_start();

include "../mysql/db_connection.php";

$user_id = $_SESSION["student_user_id"];

try {
    $stmt = $conn->prepare("SELECT question, HPS, username, due_date FROM (questions LEFT JOIN connections ON questions.question_owner_id = connections.teacher_id) LEFT JOIN accounts ON connections.teacher_id = accounts.user_id WHERE student_id = '$user_id'");
    $stmt->execute();
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
    
  } catch(PDOException $e) {
    echo $stmt . "<br>" . $e->getMessage();
  }
  
  $conn = null;

?>