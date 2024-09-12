<?php
// Start output buffering
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/crud.php');

$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

if (isset($_POST['mobile']) && isset($_POST['btnAdd'])) {
    $mobile = $db->escapeString($_POST['mobile']);
    $address_id = $db->escapeString($_POST['address_id']);
    $product_id = $db->escapeString($_POST['product_id']);
    $payment_mode = $db->escapeString($_POST['payment_mode']);

    // Query to check if the mobile number exists in the users table
    $query = "SELECT id FROM users WHERE mobile = '$mobile'";
    $db->sql($query);
    $user_result = $db->getResult();
    
    if (!empty($user_result)) {
        $user_id = $user_result[0]['id'];  // Use the existing user ID
    } else {
        // If mobile number not found, show an error and exit
        $_SESSION['error_message'] = "Mobile number not registered!";
        header('Location: create_orders.php');
        exit;
    }

    // Fetch addresses associated with the user_id from the addresses table
    $sql = "SELECT id, first_name, door_no, street_name, state, city, pincode FROM addresses WHERE user_id = '$user_id'";
    $db->sql($sql);
    $addresses = $db->getResult();

    if (empty($addresses)) {
        $_SESSION['error_message'] = "No addresses found for this user!";
        header('Location: create_orders.php');
        exit;
    }

    // Fetch product price
    $product_query = "SELECT price FROM products WHERE id = '$product_id'";
    $db->sql($product_query);
    $product = $db->getResult();

    if (empty($product)) {
        $_SESSION['error_message'] = "Product not found!";
        header('Location: create_orders.php');
        exit;
    }

    $price = $product[0]['price'];
    $delivery_charges = 0;

    if ($payment_mode == 'Prepaid') {
        $total_price = $price;  // Total price is just the product price
    } else {
        $payment_mode = 'COD';
        // Fetch delivery charges from the news table
        $delivery_charges_query = "SELECT delivery_charges FROM news ORDER BY id DESC LIMIT 1";
        $db->sql($delivery_charges_query);
        $charges_result = $db->getResult();

        if (!empty($charges_result)) {
            $delivery_charges = $charges_result[0]['delivery_charges'];
        }
        $total_price = $price + $delivery_charges;  // Add delivery charges to total price
    }

    // Live tracking URL
    $live_tracking = 'https://gmix.shiprocket.co/tracking/';

    // Current date for ordered_date
    $ordered_date = date('Y-m-d H:i:s');

    // Insert the order into the orders table
    $sql_query = "INSERT INTO orders (user_id, address_id, product_id, payment_mode, delivery_charges, total_price, live_tracking, ordered_date, price) 
                           VALUES ('$user_id', '$address_id', '$product_id', '$payment_mode', '$delivery_charges', '$total_price', '$live_tracking', '$ordered_date', '$price')";
    $db->sql($sql_query);
    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }
    if ($result == 1) {
        header("Location: create_orders.php?status=success");
        exit();
    } else {
        $error['add_balance'] = "<section class='content-header'>
                                    <span class='label label-danger'>Failed</span>
                                 </section>";
    }
}

// End output buffering and flush
ob_end_flush();
?>


<section class="content-header">
    <h1>Create Orders /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<section id='success-message' class='content-header'>
                <span class='label label-success'>Orders Added Successfully</span>
              </section>";
    } else {
        echo isset($error['add_balance']) ? $error['add_balance'] : ''; 
    }
    ?>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <div class="box">
                <div class="box-body">
                    <form id="customer_form" method="POST" action="#" class="form-horizontal">
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
                                    <?php
                                    if (!empty($addresses)) {
                                        foreach ($addresses as $value) {
                                            echo "<option value='{$value['id']}'>{$value['first_name']} - {$value['door_no']} {$value['street_name']} {$value['state']} {$value['city']} {$value['pincode']}</option>";
                                        }
                                    }
                                    ?>
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
                            <label class="col-md-4 control-label">Payment Mode:</label>
                            <div class="col-md-5">
                                <div id="payment_mode" class="btn-group pull-right">
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default" >
                                        <input type="radio" name="payment_mode" value="Prepaid" required> Prepaid
                                    </label>
                                    <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default" >
                                        <input type="radio" name="payment_mode" value="COD" required> COD
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                           <button type="submit" class="btn btn-primary" name="btnAdd">Place Order</button>
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

        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 3000);  // 3000 milliseconds = 3 seconds

        // Check if the URL contains the 'status=success' parameter
        if (window.location.href.indexOf('status=success') > -1) {
            // Remove the 'status' parameter from the URL after showing the message
            let newUrl = window.location.href.split('?')[0]; // Get URL without parameters
            history.replaceState(null, null, newUrl); // Update URL in the browser without reloading the page
        }
        $('#Addresses').on('check.bs.table', function(e, row) {
            $('#details').val(row.id + " | " + row.first_name + " | " + row.door_no + " | " + row.street_name);
            $('#address_id').val(row.id); // Update 'address_id' with the selected address's id
        });
    });
</script>
<script>
$(document).ready(function() {
    $('#checkMobile').click(function() {
        var mobile = $('#mobile').val();

        // AJAX call to fetch user_id and addresses based on mobile
        $.ajax({
            url: 'fetch_addresses.php', // Use the fetch_addresses.php script
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
});

</script>

