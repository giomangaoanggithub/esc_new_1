<?php

// this program saves the gathered information to the database for further use.

function ml_step4_save_information($input_question, $arr_goodlinks, $arr_textcontent, $question_owner_id, $grade, $due){
    include 'mysql/db_connection.php';
    $hold_str = $arr_textcontent[0];
    for($i = 0; $i < count($arr_textcontent); $i++){
        $hold_str .= '<&,&>'.$arr_textcontent[$i];
    }
    $arr_textcontent = $hold_str;

    $hold_str = $arr_goodlinks[0];
    for($i = 1; $i < count($arr_goodlinks); $i++){
        $hold_str .= '<&,&>'.$arr_goodlinks[$i];
    }
    $arr_goodlinks = $hold_str;
    
    try {
        $sql = "INSERT INTO questions (question, collected_links, documents, HPS, question_owner_id, due_date)
        VALUES ('$input_question','$arr_goodlinks', '$arr_textcontent', '$grade','$question_owner_id', '$due')";
        // use exec() because no results are returned
        $conn->exec($sql);
        $conn = null;
        echo "information saved and complete...";
    } catch(PDOException $e) {
        $conn = null;
        echo $sql . "<br>" . $e->getMessage();
    }
}

?>