<?php

// this program fetches information from the database where the questions is answered by the student

function checkstudent_step1_fetch_information($question_id){

    include 'mysql/db_connection.php';

    try {
    $stmt = $conn->prepare("SELECT * FROM questions WHERE question_id = '$question_id'");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    
    return $result[0];

    } catch(PDOException $e) {
    $conn = null;
    return "Error: " . $e->getMessage();
    }   
}

?>