<?php

function checkstudent_step2_text_normalization($student_answer, $documents){

    //normalization1 = clear "non-text-symbols"
    //normalization2 = unicode prevention
    //normalization3 = all letters are lowercased
    //normalization4 = remove stopwords
    //normalization5 = stemming

    $documents = explode('<&,&>', $documents);

    include 'foreign/list_of_stopwords.php';
    require 'foreign/porter2-master/demo/process.inc';

    $arr_content = array($student_answer);
    for($i = 0; $i < count($documents); $i++){
        array_push($arr_content, $documents[$i]);
    }

    //NORMALIZATION 1
    for($i = 0; $i < count($arr_content); $i++){
        $output = '';
        for($h = 0; $h < strlen($arr_content[$i]); $h++){
            if(ord($arr_content[$i][$h]) == 32 || ord($arr_content[$i][$h]) == 39){
                $output .= $arr_content[$i][$h];
            } else if (ord($arr_content[$i][$h]) >= 48 && ord($arr_content[$i][$h]) <= 57){
                $output .= $arr_content[$i][$h];
            } else if (ord($arr_content[$i][$h]) >= 65 && ord($arr_content[$i][$h]) <= 90){
                $output .= $arr_content[$i][$h];
            } else if (ord($arr_content[$i][$h]) >= 97 && ord($arr_content[$i][$h]) <= 122){
                $output .= $arr_content[$i][$h];
            } else {
                $output .= ' ';
            }
        }
        $arr_content[$i] = $output;
        //echo $arr_content[$i].'<br><br>';
    }

    //NORMALIZATION 2
    for($i = 0; $i < count($arr_content); $i++){
        $examine = explode(' ',$arr_content[$i]);
        // print_r($examine);
        $output = '';
        for($h = 0; $h < count($examine); $h++){
            if($examine[$h] == intval($examine[$h])){
                if($examine[$h] >= 8192 && $examine[$h] <= 8303){
                    $output .= '';
                } else {
                    $output .= $examine[$h].' '; 
                }
            } else {
                $output .= $examine[$h].' ';
            }
        }
        $arr_content[$i] = $output;
    }

    //NORMALIZATION 3
    for($i = 0; $i < count($arr_content); $i++){
        $arr_content[$i] = strtolower($arr_content[$i]);
    }

    //NORMALIZATION 4
    $arr_extracted_content = array(); //in case the user wants to see the original content
    for($i = 0; $i < count($arr_content); $i++){
        array_push($arr_extracted_content, remove_stopwords($arr_content[$i]));
    }
    $arr_origin_content = $arr_extracted_content; //to assign and track the origin of the stemmed text

    //NORMALIZATION 5
    for($i = 0; $i < count($arr_extracted_content); $i++){
        $arr_extracted_content[$i] = porterstemmer_process($arr_extracted_content[$i]);
    }

    return [$arr_extracted_content, $arr_origin_content];
}

?>