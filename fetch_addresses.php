<?php
include_once('includes/crud.php');

$db = new Database();
$db->connect();

if (isset($_POST['mobile'])) {
    $mobile = $db->escapeString($_POST['mobile']);

    // Check if the mobile number exists in the users table
    $query = "SELECT id FROM users WHERE mobile = '$mobile'";
    $db->sql($query);
    $user_result = $db->getResult();

    if (!empty($user_result)) {
        $user_id = $user_result[0]['id'];

        // Fetch addresses for the user
        $sql = "SELECT id, first_name, door_no, street_name, state, city, pincode FROM addresses WHERE user_id = '$user_id'";
        $db->sql($sql);
        $addresses = $db->getResult();

        if (!empty($addresses)) {
            // Return user_id and addresses if found
            echo json_encode([
                'success' => true,
                'user_id' => $user_id,
                'addresses' => $addresses
            ]);
        } else {
            // Return error if no addresses found
            echo json_encode([
                'success' => false,
                'message' => 'No addresses found for this user.'
            ]);
        }
    } else {
        // Return error if user not found
        echo json_encode([
            'success' => false,
            'message' => 'Mobile Number Not Registered.'
        ]);
    }
}
?>
