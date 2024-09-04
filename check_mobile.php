<?php
session_start();
include_once('includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

if (isset($_POST['mobile'])) {
    $mobile = $db->escapeString($_POST['mobile']);

    // Check if the mobile number exists in the users table
    $check_user_query = "SELECT id FROM users WHERE mobile = '$mobile'";
    $db->sql($check_user_query);
    $userData = $db->getResult();

    if (empty($userData)) {
        // Mobile number does not exist in users table, insert it
        $insert_user_query = "INSERT INTO users (mobile) VALUES ('$mobile')";
        $db->sql($insert_user_query);
        $userData = $db->getResult();

        if (!empty($userData)) {
            // Return address data if found
            echo json_encode($userData[0]);
        } 
    }

    // After ensuring the mobile number is in the users table, check the addresses table
    $sql_query = "SELECT first_name, last_name, alternate_mobile, door_no, street_name, city, pincode, state, landmark 
                  FROM addresses WHERE mobile = '$mobile'";
    $db->sql($sql_query);
    $addressesData = $db->getResult();

    if (!empty($addressesData)) {
        // Return address data if found
        echo json_encode($addressesData[0]);
    } else {
        // No address data found
        echo json_encode(["status" => "address not found", "message" => "No address data found for this mobile number."]);
    }
    exit;
}
?>
