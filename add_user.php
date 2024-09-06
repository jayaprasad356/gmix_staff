<?php
session_start();
include_once('includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

if (isset($_POST['mobile'])) {
    $mobile = $db->escapeString($_POST['mobile']);

    // Check if the mobile number exists in the users table
    $check_user_query = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($check_user_query);
    $userData = $db->getResult();

    if (!empty($userData)) {
        // Mobile number already exists in users table
        echo "Mobile number already registered";
    } else {
        // Mobile number does not exist in users table, insert it
        $insert_user_query = "INSERT INTO users (mobile) VALUES ('$mobile')";
        if($db->sql($insert_user_query)){
            echo "Mobile number inserted successfully";
        } else {
            echo "Error inserting mobile number";
        }
    }
}
?>
