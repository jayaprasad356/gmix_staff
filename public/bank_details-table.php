<?php
// Start output buffering
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('includes/crud.php');
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

// Fetch existing bank details for the logged-in user
$staffID = $_SESSION['id'];
$query = "SELECT bank, branch, ifsc, holder_name, account_num FROM staffs WHERE id = '$staffID'";
$db->sql($query);
$res = $db->getResult();
$bank_details = isset($res[0]) ? $res[0] : null; // Fetching the existing details

if (isset($_POST['btnEdit'])) {
    if (!isset($_SESSION['id'])) {
        // Redirect to login page or handle unauthorized access
        header("Location: login.php");
        exit();
    }

    // Capture form inputs and assign to variables
    $bank = $db->escapeString($_POST['bank']);
    $branch = $db->escapeString($_POST['branch']);
    $ifsc = $db->escapeString($_POST['ifsc']);
    $holder_name = $db->escapeString($_POST['holder_name']);
    $account_num = $db->escapeString($_POST['account_num']);
    $staffID = $_SESSION['id'];

    // Update query for bank details
    $update_bank_query = "UPDATE staffs SET bank = '$bank', branch = '$branch', ifsc = '$ifsc', holder_name = '$holder_name', account_num = '$account_num' WHERE id = '$staffID'";
    $db->sql($update_bank_query);

    // Check if the query executed successfully
    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        header("Location: bank_details.php?status=success");
        exit(); // Add exit after header
    } else {
        $error['add_balance'] = "<section class='content-header'>
                                    <span class='label label-danger'>Failed</span>
                                 </section>";
    }
}

// End output buffering and flush
ob_end_flush();
?>

<!-- Form HTML -->
<section class="content-header">
    <h1>Update Bank Details /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<section id='success-message' class='content-header'>
                <span class='label label-success'>Bank Details Updated Successfully</span>
              </section>";
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
                    <!-- Form to update bank details -->
                    <form id="bank_Details" method="POST" action="#" class="form-horizontal" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label for="bank" class="col-sm-3 control-label">Bank Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bank" id="bank" placeholder="Enter Bank Name" value="<?php echo isset($bank_details['bank']) ? $bank_details['bank'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch" class="col-sm-3 control-label">Branch</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="branch" id="branch" placeholder="Enter Branch Name" value="<?php echo isset($bank_details['branch']) ? $bank_details['branch'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ifsc" class="col-sm-3 control-label">IFSC Code</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="ifsc" id="ifsc" placeholder="Enter IFSC Code" value="<?php echo isset($bank_details['ifsc']) ? $bank_details['ifsc'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="holder_name" class="col-sm-3 control-label">Account Holder Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="holder_name" id="holder_name" placeholder="Enter Account Holder Name" value="<?php echo isset($bank_details['holder_name']) ? $bank_details['holder_name'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="account_num" class="col-sm-3 control-label">Account Number</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="account_num" id="account_num" placeholder="Enter Account Number" value="<?php echo isset($bank_details['account_num']) ? $bank_details['account_num'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
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
