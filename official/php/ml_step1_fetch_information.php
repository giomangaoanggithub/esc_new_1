<?php

//ml_step1_fetch_information this program fetches the information from the internet to gather data for the algorithm to read and learn it

function ml_step1_fetch_information($input_question){
    include 'foreign/simple_html_dom.php';
    include 'mysql/db_connection.php';
    // echo 'fetching information from the internet...<br><br>';
    $linkform_question = str_replace(' ', '+', $input_question);
    $arr_links = array();
    $arr_goodlinks = array();
    $arr_textcontent = array();
    $html = file_get_html('https://www.google.com/search?q='.$linkform_question);
    foreach($html->find('a') as $element) {
        if(str_contains($element->href, 'https') && str_contains($element->href, 'google') != 'google' && str_contains($element->href, 'researchgate') != 'researchgate'){
            $raw = $element->href;
            $x = strpos($raw, 'https://');
            $y = strpos($raw, 'sa=') - 1;
            $string = '';
            while($x < $y && $raw[$x] != '&'){
                $string .= $raw[$x];
                $x++;
            }
            array_push($arr_links, $string);
            // echo $string.'<br>';
        }
    }
    //print_r($arr_links);
    // echo '<br><br>';
    for($i = 0; $i < count($arr_links); $i++){
        $output = file_get_html($arr_links[$i])->plaintext;
        if(str_word_count($output) > 99){
            //echo $output.'<br><br>';
            array_push($arr_goodlinks, $arr_links[$i]);
            array_push($arr_textcontent, $output);
        }
        file_get_html($arr_links[$i])->clear;
    }
    // echo '<br><br>';
    //print_r($arr_textcontent);
    //echo '<br><br>';

    // echo 'information fetching complete...<br><br>saving information...<br><br>';

    return array($arr_textcontent, $arr_goodlinks);
}
?>