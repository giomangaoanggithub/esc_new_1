<?php

// this program runs all other programs in relation to the question creation to run the whole operation.

session_start();

include 'dummy_data/test_step1_fetch_information.php';
include 'dummy_data/test_step4_learn_context.php';

include 'ml_step1_fetch_information.php';
include 'ml_step2_reduce_noise.php';
include 'ml_step3_text_normalization.php';
include 'ml_step4_save_information.php';

error_reporting(0);

// $user_input = 'In what way is conducting a research useful/helpful to the society?';

$user_id = $_SESSION["teacher_user_id"];
$user_input = $_POST["question"];
$user_grade = $_POST["hps"];
$due_date = $_POST["due"];

$start_step1 = microtime(true);
$step1 = ml_step1_fetch_information($user_input); //print_r($step1); // output: array[array (text content), array (URLs)]
// $step1[0] = $test_sample;
// $step1[1] = $test_sample_links;
$end_step1 = microtime(true) - $start_step1;
$start_step2 = microtime(true);
$step2 = ml_step2_reduce_noise($step1[0]);
$end_step2 = microtime(true) - $start_step2;
$start_step3 = microtime(true);
$step3 = ml_step3_text_normalization($step2);
$end_step3 = microtime(true) - $start_step3;
$start_step4 = microtime(true);
$step4 = ml_step4_save_information($user_input, $step1[1], $step3, $user_id, $user_grade, $due_date);
$end_step4 = microtime(true) - $start_step4;

// echo '<br><br>';
// echo 'Step 1 execution time: '.$end_step1.'<br>';
// echo 'Step 2 execution time: '.$end_step2.'<br>';
// echo 'Step 3 execution time: '.$end_step3.'<br>';
// echo 'Step 4 execution time: '.$end_step4.'<br>';
?>