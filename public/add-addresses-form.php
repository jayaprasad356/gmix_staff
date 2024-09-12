<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
?>
<?php
if (isset($_POST['btnAdd'])) {
    $user_id = $db->escapeString($_POST['user_id']);
    $first_name = $db->escapeString($_POST['first_name']);
    $last_name = $db->escapeString($_POST['last_name']);
    $mobile = $db->escapeString($_POST['mobile']);
    $alternate_mobile = $db->escapeString($_POST['alternate_mobile']);
    $door_no = $db->escapeString($_POST['door_no']);
    $street_name = $db->escapeString($_POST['street_name']);
    $city = $db->escapeString($_POST['city']);
    $pincode = $db->escapeString($_POST['pincode']);
    $state = $db->escapeString($_POST['state']);
    $landmark = $db->escapeString($_POST['landmark']);

    if (empty($last_name)) {
        $last_name = $first_name;
    }

    if (empty($user_id)) {
        $error['user_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($first_name)) {
        $error['first_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($mobile)) {
        $error['mobile'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($door_no)) {
        $error['door_no'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($street_name)) {
        $error['street_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($city)) {
        $error['city'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($pincode)) {
        $error['pincode'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($state)) {
        $error['state'] = " <span class='label label-danger'>Required!</span>";
    }

      // Check if mobile and alternate_mobile are the same
      if ($mobile === $alternate_mobile) {
        $error['mobile_match'] = " <span class='label label-danger'>Mobile and Alternate Mobile cannot be the same!</span>";
    }

    // Only proceed if there are no errors
    if (!isset($error) && !empty($user_id) && !empty($first_name) && !empty($mobile) && !empty($door_no) && !empty($street_name) && !empty($city) && !empty($pincode) && !empty($state)) {

        $sql_query = "INSERT INTO addresses (user_id, first_name, last_name, mobile, alternate_mobile, door_no, street_name, city, pincode, state, landmark)
        VALUES ('$user_id', '$first_name', '$last_name', '$mobile', '$alternate_mobile', '$door_no', '$street_name', '$city', '$pincode', '$state', '$landmark')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }
        if ($result == 1) {
            header("Location: add-addresses.php?status=success");
            exit();
        } else {
            $error['add_balance'] = "<section class='content-header'>
                                        <span class='label label-danger'>Failed</span>
                                     </section>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add Addresses <small><a href='addresses.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Addresses</a></small></h1>
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<section class='content-header'>
                <span class='label label-success'>Addresses Added Successfully</span>
              </section>";
    } else {
        echo isset($error['add_balance']) ? $error['add_balance'] : ''; 
    }
    ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_project_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="">Users</label>
                            <input type="text" id="details" name="user_id" class="form-control" readonly>
                            <input type="hidden" id="user_id" name="user_id" value="">
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name (Optional)</label>
                            <input type="text" id="last_name" name="last_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" id="mobile" name="mobile" class="form-control">
                            <?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="alternate_mobile">Alternate Mobile</label>
                            <input type="text" id="alternate_mobile" name="alternate_mobile" class="form-control">
                            <?php echo isset($error['mobile_match']) ? $error['mobile_match'] : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="door_no">Door No.</label>
                            <input type="text" id="door_no" name="door_no" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="street_name">Street Name</label>
                            <input type="text" id="street_name" name="street_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" id="pincode" name="pincode" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="landmark">Landmark  (Optional)</label>
                            <input type="text" id="landmark" name="landmark" class="form-control">
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="submit_btn" name="btnAdd">Submit</button>
                        <input type="reset" class="btn-warning btn" value="Clear" />
                    </div>
                    <div class="form-group">
                        <div id="result" style="display: none;"></div>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
        <!-- Left col -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Users</h3>
                    <button type="button" class="btn btn-sm btn-default pull-right" data-toggle="modal" data-target="#addUserModal">
                        <i class="fa fa-plus-square"></i> Add Users
                    </button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="users" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-trim-on-search="false" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "users-list-<?= date('d-m-y') ?>",
                        "ignoreColumn": ["state"]
                    }'>
                        <thead>
                        <tr>
                                <th data-field="state" data-radio="true"></th>
                                <th data-field="id" data-sortable="true">User ID</th> <!-- Ensure 'id' (user_id) is being fetched -->
                                <th data-field="mobile" data-sortable="true">Mobile</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="separator"> </div>
    </div>
</section>

<!-- Bootstrap Modal for Adding Users -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addUserForm" method="post">
                <div class="modal-body">
                    <!-- Error/Success Message Area -->
                    <div id="modalMessage" class="alert d-none" role="alert"></div>

                    <div class="form-group">
                        <label for="modalMobile">Mobile</label>
                        <input type="text" class="form-control" id="modalMobile" name="mobile" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitUser">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('#submitUser').click(function () {
            // Get the mobile input value
            var mobile = $('#modalMobile').val();

            // Clear previous messages
            $('#modalMessage').removeClass('d-none alert-success alert-danger').text('');

            $.ajax({
                url: 'add_user.php', // Update with the actual PHP file URL
                method: 'POST',
                data: { mobile: mobile },
                success: function (response) {
                    if (response.includes('successfully')) {
                        $('#modalMessage').addClass('alert-success').text(response).removeClass('d-none');
                    } else {
                        $('#modalMessage').addClass('alert-danger').text(response).removeClass('d-none');
                    }
                },
                error: function (xhr, status, error) {
                    $('#modalMessage').addClass('alert-danger').text('An error occurred: ' + error).removeClass('d-none');
                }
            });
        });
    });
</script>

<script>
    // When a user is selected from the table, fill in the details
    $('#users').on('check.bs.table', function (e, row) {
        // Set the 'mobile' field with the selected user's mobile number
        $('#details').val(row.mobile);

        // Set the 'user_id' hidden field with the selected user's ID
        $('#user_id').val(row.id); // Now setting the 'user_id' with the actual user ID
    });
</script>

