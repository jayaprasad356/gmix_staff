<?php

include_once('includes/crud.php');
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the form has been submitted
if (isset($_POST['mobile']) && isset($_POST['btnAdd'])) {
    $mobile = $db->escapeString($_POST['mobile']);
    $address_id = $db->escapeString($_POST['address_id']);
    $product_id = $db->escapeString($_POST['product_id']);
    
    // Query to check if the mobile number exists in the users table
    $query = "SELECT id FROM users WHERE mobile = '$mobile'";
    $db->sql($query);
    $user_result = $db->getResult();
    
    if (!empty($user_result)) {
        $user_id = $user_result[0]['id'];  // Use the existing user ID
    } else {
        // If mobile number not found, show an error and exit
        $_SESSION['error_message'] = "Mobile number not registered!";
        header('Location: create_payment_links.php');
        exit;
    }

    // Fetch addresses associated with the user_id from the addresses table
    $sql = "SELECT id, first_name, door_no, street_name, state, city, pincode FROM addresses WHERE user_id = '$user_id'";
    $db->sql($sql);
    $addresses = $db->getResult();

    if (empty($addresses)) {
        $_SESSION['error_message'] = "No addresses found for this user!";
        header('Location: create_payment_links.php');
        exit;
    }

    // Fetch product price and quantity
    $product_query = "SELECT price, quantity FROM products WHERE id = '$product_id'";
    $db->sql($product_query);
    $product = $db->getResult();

    if (empty($product)) {
        $_SESSION['error_message'] = "Product not found!";
        header('Location: create_payment_links.php');
        exit;
    }

    $price = $product[0]['price'];

    // Fetch additional information required for API
    $address_query = "SELECT first_name, mobile FROM addresses WHERE id = '$address_id'";
    $db->sql($address_query);
    $address_info = $db->getResult();
    
    if (empty($address_info)) {
        $_SESSION['error_message'] = "Address not found!";
        header('Location: create_payment_links.php');
        exit;
    }
    
    // Prepare API data
    $buyer_name = $address_info[0]['first_name'];
    $phone = $address_info[0]['mobile'];
    $amount = $product[0]['price'];
    $quantity = 1;
    $staff_id = $_SESSION['id'];

    // API URL
    $api_url = "https://gateway.graymatterworks.com/api/create_payment_request.php";
    
    // Prepare data for API call
    $data = [
        'purpose' => "{$user_id}-{$address_id}-{$product_id}-{$quantity}-{$staff_id}",
        'buyer_name' => $buyer_name,
        'amount' => $amount,
        'email' => 'default@example.com', // Set default email
        'phone' => $phone
    ];

    // Use cURL to send data to API
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Decode the response
    $response_data = json_decode($response, true);

   // If 'others' payment option is selected, handle image upload and order insertion
   if (isset($_POST['payment_option']) && $_POST['payment_option'] === 'others') {
    if (isset($_FILES['payment_image']) && $_FILES['payment_image']['size'] > 0 && $_FILES['payment_image']['error'] == 0) {
        $extension = pathinfo($_FILES["payment_image"]["name"], PATHINFO_EXTENSION);
        $result = $fn->validate_image($_FILES["payment_image"]);
        $target_path = 'upload/images/';
        $payment_image = microtime(true) . '.' . strtolower($extension);
        $payment_image_full_path = $target_path . $payment_image;

        if (!move_uploaded_file($_FILES["payment_image"]["tmp_name"], $payment_image_full_path)) {
            $_SESSION['error_message'] = "Cannot upload payment image.";
            header("Location: create_payment_links.php");
            exit();
        }
        
        $staffID = $_SESSION['id'];
        $ordered_date = date('Y-m-d H:i:s');
        $total_price = $price;
        $live_tracking = 'https://gmix.shiprocket.co/tracking/';
        $status = '6';

        $sql_query = "INSERT INTO orders (user_id, address_id, product_id, payment_mode, delivery_charges, total_price, live_tracking, ordered_date, price, created_at, payment_image, status, quantity, staff_id)
                      VALUES ('$user_id', '$address_id', '$product_id', 'Prepaid', '0', '$total_price', '$live_tracking', '$ordered_date', '$price', NOW(), '$payment_image_full_path', '$status', 1, '$staffID')";
        $db->sql($sql_query);

        $_SESSION['success_message'] = "Order placed successfully with uploaded payment image!";
        header("Location: orders.php?status=success");
        exit();
    } else {
        $_SESSION['error_message'] = "Please upload a payment image!";
        header("Location: create_payment_links.php");
        exit();
    }
}


    // Check if the API call was successful
    if (isset($response_data['longurl'])) {
        $payment_link = $response_data['longurl'];

        // Insert the user_id and payment link (long URL) into the payment_links table
        $sql_query = "INSERT INTO payment_links (user_id, payment_link) VALUES ('$user_id', '$payment_link')";
        $db->sql($sql_query);

        // Set the payment link in the session to display it after redirection
        $_SESSION['payment_link'] = $payment_link;
        
        header("Location: payment_links.php?status=success");
        exit();
    } else {
        $_SESSION['error_message'] = "Payment link creation failed!";
        header('Location: create_payment_links.php');
        exit;
    }
}
    // End output buffering and flush
    ob_end_flush();
    ?>

    <section class="content-header">
    <h1>Create Payment Links <small><a href='payment_links.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Payment Links</a></small></h1>
        <?php 
        if (isset($_GET['status']) && $_GET['status'] == 'success') {
            echo "<section id='success-message' class='content-header'>
                    <span class='label label-success'>Payment Link Created Successfully</span>
                  </section>";
            // Clear the payment link after displaying
            unset($_SESSION['payment_link']);
        } else {
            echo isset($error['add_balance']) ? $error['add_balance'] : ''; 
        }
        ?>
    </section>

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <form id="customer_form" method="POST" action="#" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="mobile" class="col-sm-4 control-label">Mobile Number:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" required>
                                    <input type="hidden" id="user_id" name="user_id" value="">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" id="checkMobile">Submit</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_id" class="col-sm-4 control-label">Select Addresses:</label>
                            <div class="col-sm-8">
                                <select id='address_id' name="address_id" class='form-control' required>
                                    <option value="">Select Address</option>
                                    <!-- Address options will be populated here by AJAX -->
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product_id" class="col-sm-4 control-label">Select Product:</label>
                            <div class="col-sm-8">
                                <select id='product_id' name="product_id" class='form-control' required>
                                    <?php
                                    $sql = "SELECT id, name, price, measurement, unit FROM products";
                                    $db->sql($sql);
                                    $result = $db->getResult();
                                    foreach ($result as $value) {
                                        echo "<option value='{$value['id']}'>{$value['name']} - {$value['price']} - {$value['measurement']}{$value['unit']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Payment Option:</label>
                            <div class="col-sm-8">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="payment_option" value="payment_link" checked> Payment Link
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="payment_option" value="others"> Others
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="upload-screenshot" style="display: none;">
                            <label for="payment_image" class="col-sm-4 control-label">Upload Payment Screenshot:</label>
                            <div class="col-sm-8">
                                <input type="file" name="payment_image" id="payment_image" class="form-control">
                            </div>
                        </div>


                        <div class="box-footer">
                           <button type="submit" class="btn btn-primary" name="btnAdd" id="submitButton">Create Payment Links</button>
                           <input type="reset" class="btn-warning btn" value="Clear" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="separator"></div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Toggle display based on selected payment option
        $('input[name="payment_option"]').change(function() {
            if ($(this).val() === 'others') {
                $('#upload-screenshot').show();
                $('#submitButton').text('Send for Verification');
            } else {
                $('#upload-screenshot').hide();
                $('#submitButton').text('Create Payment Links');
            }
        });

        // Form submission validation
        $('#customer_form').submit(function(event) {
            var selectedOption = $('input[name="payment_option"]:checked').val();
            var paymentImage = $('#payment_image').val();

            if (selectedOption === 'others' && !paymentImage) {
                // Prevent form submission
                event.preventDefault();
                // Display error message
                alert('You need to upload a payment screenshot image for "Others" payment option.');
            }
        });

        // Check if the URL contains the 'status=success' parameter
        if (window.location.href.indexOf('status=success') > -1) {
            // Remove the 'status' parameter from the URL after showing the message
            let newUrl = window.location.href.split('?')[0]; // Get URL without parameters
            history.replaceState(null, null, newUrl); // Update URL in the browser without reloading the page
        }

        $('#checkMobile').click(function() {
            var mobile = $('#mobile').val();

            // AJAX call to fetch user_id and addresses based on mobile
            $.ajax({
                url: 'get_addresses.php', // Use the fetch_addresses.php script
                type: 'POST',
                data: { mobile: mobile },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.success) {
                        $('#user_id').val(data.user_id); // Set user_id
                        var addressOptions = '';

                        // Loop through the addresses and append to the dropdown
                        data.addresses.forEach(function(address) {
                            addressOptions += `<option value="${address.id}">${address.first_name} - ${address.door_no} ${address.street_name} ${address.state} ${address.city} ${address.pincode}</option>`;
                        });

                        $('#address_id').html(addressOptions); // Update address dropdown
                    } else {
                        alert(data.message); // Show error message
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }   
            });
        });

        // Copy payment link to clipboard
        $('#copyLink').click(function() {
            var paymentLink = $('#paymentLink');
            paymentLink.select(); // Select the text
            document.execCommand('copy'); // Copy to clipboard
            alert('Payment link copied to clipboard!'); // Show confirmation
            sessionStorage.setItem('paymentLinkCopied', 'true'); // Set session storage item
        });
    });
</script>
