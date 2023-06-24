<?php

function checkstudent_step3_learn_context($arr_documents){

    include 'foreign/cosine_similarity.php';

    $arr_extracted_original = array(); //pairing stemmed and non-stemmed by words
    $arr_cleansed_e_o = array(); //cleaning empty and useless elements
    $arr_counted_terms = array(); //counting term frequency
    $arr_terms_per_doc = array(); //terms with document origins
    for($i = 0; $i < count($arr_documents[1]); $i++){
        array_push($arr_extracted_original, array(explode(' ',$arr_documents[0][$i]), explode(' ',$arr_documents[1][$i])));
    }
    // print_r($arr_extracted_original);
    for($i = 0; $i < count($arr_extracted_original); $i++){
        $hold_arr = array();
        for($h = 0; $h < count($arr_extracted_original[$i][0]); $h++){
            if(strlen($arr_extracted_original[$i][1][$h]) > 1){
                array_push($arr_cleansed_e_o, array($arr_extracted_original[$i][0][$h], $arr_extracted_original[$i][1][$h]));
                array_push($arr_counted_terms, $arr_extracted_original[$i][0][$h]);
                array_push($hold_arr, $arr_extracted_original[$i][0][$h]);
                //echo $arr_extracted_original[$i][0][$h].' == '.$arr_extracted_original[$i][1][$h].'<br><br>';
            }
        }
        array_push($arr_terms_per_doc, $hold_arr);
    }
    $arr_points_countedterms = array_values(array_count_values($arr_counted_terms)); // checking overall occurences of words
    $arr_counted_terms = array_values(array_unique($arr_counted_terms)); // horizontal values
    $arr_terms = array();
    for($i = 0; $i < count($arr_cleansed_e_o); $i++){
        array_push($arr_terms, $arr_cleansed_e_o[$i][0]);
    }
    // print_r($arr_term_id);
    $arr_term_freq_doc = array(); // inputting horizontal values with respect to its vertical values
    // print_r($arr_counted_terms);
    $arr_total_words_per_doc = array(); // total words per doc
    for($i = 0; $i < count($arr_terms_per_doc); $i++){
        $hold_arr = array_count_values($arr_terms_per_doc[$i]);
        $hold_arr_tfd = array_fill(0, count($arr_counted_terms), 0);
        for($h = 0; $h < count($arr_counted_terms); $h++){
            $hold_arr_tfd[$h] = $hold_arr[$arr_counted_terms[$h]];
        }
        array_push($arr_term_freq_doc, $hold_arr_tfd);
    }
    for($i = 0; $i < count($arr_term_freq_doc); $i++){
        $hold_num_words = 0;
        for($h = 0; $h < count($arr_term_freq_doc[$i]); $h++){
            $hold_num_words += $arr_term_freq_doc[$i][$h];
        }
        array_push($arr_total_words_per_doc, $hold_num_words);
    }

    // TERM FREQUENCY
    
    $arr_term_frequency = array();
    $inverse_doc_frequency = array();
    $arr_occurence_words_doc = array_fill(0, count($arr_counted_terms), 0);
    for($i = 0; $i < count($arr_total_words_per_doc); $i++){
        $hold_arr = array();
        for($h = 0; $h < count($arr_term_freq_doc[$i]); $h++){
            array_push($hold_arr, round($arr_term_freq_doc[$i][$h] / $arr_total_words_per_doc[$i], 5));
        }
        array_push($arr_term_frequency, $hold_arr);
    }
    // echo count($arr_counted_terms);
    
    // INVERSE DOCUMENT FREQUENCY

    for($i = 0; $i < count($arr_counted_terms); $i++){
        for($h = 0; $h < count($arr_term_frequency); $h++){
            if($arr_term_frequency[$h][$i] > 0){
                $arr_occurence_words_doc[$i]++;
            }
        }
    }
    // print_r($arr_occurence_words_doc);
    for($i = 0; $i < count($arr_occurence_words_doc); $i++){
        array_push($inverse_doc_frequency, round(log10(count($arr_term_frequency) / $arr_occurence_words_doc[$i]), 5));
    }
    // print_r($inverse_doc_frequency);
    // echo '<br><br>';
    // print_r(count($arr_term_frequency[0]));
    $arr_tf_idf = array();
    for($i = 0; $i < count($arr_term_frequency); $i++){
        $hold_arr = array();
        for($h = 0; $h < count($arr_term_frequency[$i]); $h++){
            array_push($hold_arr, round($arr_term_frequency[$i][$h] * $inverse_doc_frequency[$h], 5));
        }
        array_push($arr_tf_idf, $hold_arr);
    }
    // print_r($arr_tf_idf);
    $arr_traced_terms = array();
    for($i = 0; $i < count($inverse_doc_frequency); $i++){
        array_push($arr_traced_terms, $arr_cleansed_e_o[array_search($arr_counted_terms[$i], $arr_terms)][1]);
    }
    // print_r($arr_traced_terms);

    $custom_traced_terms = array();

    for($i = 0; $i < count($arr_traced_terms); $i++){
        $custom_traced_terms[$arr_traced_terms[$i]] = $inverse_doc_frequency[$i];
    }
    natsort($custom_traced_terms);

    // print_r($custom_traced_terms);
    // echo "<br><br>";
    $ranked_numbers = array();
    $ranked_words = array();
    $str_topwords = "";

    foreach($custom_traced_terms as $key => $element){
        array_push($ranked_numbers, $element);
        array_push($ranked_words, $key);
    }
    // print_r($ranked_numbers);
    // print_r($ranked_words);

    for($i = 0; $i < 60; $i++){
        $str_topwords .= ",".$ranked_words[$i];
    }

    $best_score = 0;
    for($i = 1; $i < count($arr_tf_idf); $i++){
        // echo cosine_sim($arr_tf_idf[0], $arr_tf_idf[$i]).'<br>';
        if($best_score < cosine_sim($arr_tf_idf[0], $arr_tf_idf[$i])){
            $best_score = cosine_sim($arr_tf_idf[0], $arr_tf_idf[$i]);
        }
    }
    $best_score = round($best_score * 1000, 2);

    // if($best_score > 70){
    //     $best_score = 'Accurate '.$best_score.'%';
    // } else if ($best_score >= 50){
    //     $best_score = 'Correct '.$best_score.'%';
    // } else {
    //     $best_score = 'Inaccurate '.$best_score.'%';
    // }

    return $best_score.$str_topwords;
}

?>