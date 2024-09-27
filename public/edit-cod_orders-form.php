<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    header("Location: cod_orders.php");
    exit(0);
}

// Initialize variables
$attempt1_disabled = '';
$status = 5; // Default status
$attempt1 = '';

if (isset($_POST['btnEdit'])) {
    $attempt1 = isset($_POST['attempt1']) ? $db->escapeString($_POST['attempt1']) : '';
    $error = array();

    // Fetch ordered_date for the given ID
    $sql_query = "SELECT ordered_date FROM orders WHERE id = $ID";
    $db->sql($sql_query);
    $order = $db->getResult();

    if (empty($order)) {
        $error['update_languages'] = "<span class='label label-danger'>Order not found</span>";
    } else {
        $ordered_date = $order[0]['ordered_date'];
        $today = date('Y-m-d');

        // Determine field states and status
        if ($today === $ordered_date) {
            // If today is the ordered_date, disable attempt1
            $attempt1_disabled = 'disabled';
            $error['update_languages'] = "<span class='label label-danger'>Cannot update attempt1 today. Field is disabled.</span>";
        } else {
            // If today is not the ordered_date, enable attempt1 and update it
            $attempt1_disabled = '';
            $update_message = '';

            if (!empty($attempt1)) {
                $sql_query = "UPDATE orders SET attempt1='$attempt1', status = 0 WHERE id = $ID";
                $db->sql($sql_query);
                $update_result = $db->getResult();
                if ($db->getAffectedRows() > 0) {
                    $update_message = "<span class='label label-success'>Attempt 1 updated successfully.</span>";
                } else {
                    $update_message = "<span class='label label-danger'>Failed to update Attempt 1.</span>";
                }
            }

            $error['update_languages'] = $update_message;
        }
    }
}

$sql_query = "SELECT * FROM orders WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();
$ordered_date = isset($res[0]['ordered_date']) ? $res[0]['ordered_date'] : '';

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "cod_orders.php";
    </script>
<?php } ?>

<section class="content-header">
    <h1>
        Edit Orders<small><a href='cod_orders.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Orders</a></small></h1>
    <small><?php echo isset($error['update_languages']) ? $error['update_languages'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="edit_languages_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="ordered_date" value="<?php echo htmlspecialchars($ordered_date); ?>">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="attempt1">Attempt 1</label><i class="text-danger asterik">*</i>
                                    <textarea class="form-control" name="attempt1" id="attempt1" <?php echo $attempt1_disabled; ?>><?php echo isset($res[0]['attempt1']) ? htmlspecialchars($res[0]['attempt1']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                        </div>
                    </div>
                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var orderedDate = document.getElementById('ordered_date').value;
        var attempt1Field = document.getElementById('attempt1');
        var today = new Date().toISOString().split('T')[0]; // Current date in YYYY-MM-DD format

        // Convert ordered_date to YYYY-MM-DD format for comparison
        var orderedDateFormatted = new Date(orderedDate).toISOString().split('T')[0];

        if (today === orderedDateFormatted) {
            // Disable attempt1 if ordered_date is today
            attempt1Field.disabled = true;
        } else {
            // Enable attempt1 if ordered_date is not today
            attempt1Field.disabled = false;
        }
    });
</script>
