<?php

// this program reduces the noise gathered from the fetched information. it does not completely eliminate it, but to reduce the
// unwanted words from the source websites

function ml_step2_reduce_noise($arr_content){
    $arr_refinedcontent = array();
    // echo '<br><br>';
    for($i = 0; $i < count($arr_content); $i++){
        $output = '';
        $sample = explode(' ',$arr_content[$i]);
        $clean_sample = array();
        for($h = 0; $h < count($sample); $h++){
            if((ord($sample[$h]) != '0' && strlen($sample[$h]) > 1) && (ord($sample[$h]) != '9' && strlen($sample[$h]) > 1) && (ord($sample[$h]) != '10' && strlen($sample[$h]) > 1) && (ord($sample[$h]) != '13' && strlen($sample[$h]) > 1) && (ord($sample[$h]) != '60' && strlen($sample[$h]) > 1)){
                //echo "sample_word: ".$sample[$h]." | ord: ".ord($sample[$h]);
                array_push($clean_sample, $sample[$h]);
            }
            
        }
        // print_r($clean_sample);
        for($h = (int)((count($clean_sample) - (count($clean_sample) * 0.7)) / 2); $h <= (int)(count($clean_sample) - ((count($clean_sample) - (count($clean_sample) * 0.7)) / 2)); $h++){
            $output .= $clean_sample[$h].' ';
        }
        array_push($arr_refinedcontent, $output);
    }
    // echo '<br><br>';
    // print_r($arr_refinedcontent);
    // echo '<br><br>';
    // for($i = 0; $i < count($arr_content); $i++){
    //     echo str_word_count($arr_content[$i]).' | ';
    // }
    // echo '<br><br>';
    // for($i = 0; $i < count($arr_refinedcontent); $i++){
    //     echo str_word_count($arr_refinedcontent[$i]).' | ';
    // }
    return $arr_refinedcontent;
}
?>