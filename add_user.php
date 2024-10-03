<?php
session_start();
include_once('includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

if (isset($_POST['mobile'])) {
    $mobile = $db->escapeString($_POST['mobile']);
    $created_at = date('Y-m-d H:i:s'); // Get the current date and time

    // Check if the mobile number is exactly 10 digits
    if (preg_match('/^[0-9]{10}$/', $mobile)) {
        // Check if the mobile number exists in the users table
        $check_user_query = "SELECT * FROM users WHERE mobile = '$mobile'";
        $db->sql($check_user_query);
        $userData = $db->getResult();

        if (!empty($userData)) {
            // Mobile number already exists in users table
            echo "Mobile number already registered";
        } else {
           // $staffID = $_SESSION['id'];
            // Mobile number does not exist in users table, insert it
            $insert_user_query = "INSERT INTO users (mobile,created_at) VALUES ('$mobile','$created_at')";
            if ($db->sql($insert_user_query)) {
                echo "Mobile number inserted successfully";
            } else {
                echo "Error inserting mobile number";
            }
        }
    } else {
        // Mobile number is not valid
        echo "Please enter a valid 10-digit mobile number";
    }
}
?>
