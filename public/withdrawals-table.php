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

// Fetch existing bank details and incentives for the logged-in user
$staffID = $_SESSION['id'];
$query = "SELECT incentives, bank, branch, ifsc, holder_name, account_num FROM staffs WHERE id = '$staffID'";
$db->sql($query);
$res = $db->getResult();
$bank_details = isset($res[0]) ? $res[0] : null; // Fetching the existing details

// Check if the form is submitted
if (isset($_POST['btnWithdraw'])) {
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    // Capture the withdrawal amount
    $withdraw_amount = $db->escapeString($_POST['withdraw_amount']);
    
    // Check if bank details are complete
    if (empty($bank_details['bank']) || empty($bank_details['branch']) || empty($bank_details['ifsc']) || empty($bank_details['holder_name']) || empty($bank_details['account_num'])) {
        $error['withdrawal'] = "<section class='content-header'>
                                    <span class='label label-danger'>Please update your bank details before making a withdrawal.</span>
                                </section>";
    } 
    // Check if the withdrawal amount is less than or equal to incentives
    elseif ($withdraw_amount > $bank_details['incentives']) {
        $error['withdrawal'] = "<section class='content-header'>
                                    <span class='label label-danger'>Insufficient Balance.</span>
                                </section>";
    } 
    else {
        // Insert the withdrawal record into withdrawals table
        $datetime = date('Y-m-d H:i:s');
        $insert_query = "INSERT INTO withdrawals (staff_id, amount, status, datetime) VALUES ('$staffID', '$withdraw_amount', 0, '$datetime')";
        $db->sql($insert_query);

        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            $update_incentives_query = "UPDATE staffs SET incentives = incentives - $withdraw_amount WHERE id = '$staffID'";
            $db->sql($update_incentives_query);
            header("Location: withdrawals.php?status=success");
            exit();
        } else {
            $error['withdrawal'] = "<section class='content-header'>
                                        <span class='label label-danger'>Failed to process withdrawal request.</span>
                                    </section>";
        }
    }
}

// End output buffering and flush
ob_end_flush();
?>

<!-- Form HTML -->
<section class="content-header">
    <h1>Withdrawal /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<section id='success-message' class='content-header'>
                <span class='label label-success'>Withdrawal Successfully</span>
              </section>";
    } else {
        echo isset($error['withdrawal']) ? $error['withdrawal'] : ''; 
    }
    ?>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    <!-- Display available incentives -->
                    <p>Your available incentives: <strong>₹<?php echo $bank_details['incentives']; ?></strong></p>
<br>
                    <!-- Withdrawal form -->
                    <form id="withdrawal_form" method="POST" action="#" class="form-horizontal" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label for="withdraw_amount" class="col-sm-3 control-label">Withdrawal Amount</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="withdraw_amount" id="withdraw_amount" placeholder="Enter amount to withdraw" required>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="btnWithdraw">Withdraw</button>
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
    });
</script>
