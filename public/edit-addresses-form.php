<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
	$ID = $db->escapeString($_GET['id']);
} else {
	// $ID = "";
	return false;
	exit(0);
}

    if (isset($_POST['btnEdit'])) {
        // Escape input fields
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
    
        $error = array();
    
            // Update query
            $sql_query = "UPDATE addresses SET user_id='$user_id',first_name='$first_name', last_name='$last_name', mobile='$mobile', alternate_mobile='$alternate_mobile',
                door_no='$door_no', street_name='$street_name', city='$city', pincode='$pincode', state='$state', landmark='$landmark' WHERE id = $ID";
            $db->sql($sql_query);
            $update_result = $db->getResult();
    
            // Check the result of the update operation
            if (!empty($update_result)) {
                $update_result = 0;
            } else {
                $update_result = 1;
            }
    
            if ($update_result == 1) {
                $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Addresses updated Successfully</span></section>";
            } else {
                $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
            }
        }
    
    
    // Create array variable to store previous data
    $data = array();
    
    // Fetch previous data for result
    $sql_query = "SELECT * FROM addresses WHERE id = $ID";
    $db->sql($sql_query);
    $res = $db->getResult();
    
    $user_id = $res[0]['user_id'];
    $sql_query_user = "SELECT id, mobile FROM users WHERE id = $user_id";
    $db->sql($sql_query_user);
    $result = $db->getResult();
    
    if (isset($_POST['btnCancel'])) { ?>
        <script>
            window.location.href = "addresses.php";
        </script>
    <?php } ?>
    
    <section class="content-header">
        <h1>
            Edit Addresses<small><a href='addresses.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Addresses</a></small></h1>
        <small><?php echo isset($error['update_jobs']) ? $error['update_jobs'] : ''; ?></small>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
        </ol>
    </section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-6">

			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
				</div><!-- /.box-header -->
				<!-- form start -->
				<form name="add_slide_form" method="post" enctype="multipart/form-data">
				<div class="box-body">
				<div class="form-group">
                            <label for="">Users</label>
                            <?php if (!empty($result) && isset($result[0]['id'],$result[0]['mobile'])) : ?>
                                <?php $userDetails = $result[0]; ?>
                                <input type="text" id="details" name="user_id" class="form-control" value="<?php echo $userDetails['id'] . ' | '  . $userDetails['mobile']; ?>" disabled>
                            <?php else : ?>
                                <input type="text" id="details" name="user_id" class="form-control" value="User details not available" disabled>
                            <?php endif; ?>
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $res[0]['user_id']; ?>">
                        </div>
                        <div class="form-group">
							<label for="">First Name</label>
							<input type="text" class="form-control" name="first_name" value="<?php echo $res[0]['first_name']?>">
						</div>
						<div class="form-group">
							<label for="">Last Name</label>
							<input type="text" class="form-control" name="last_name" value="<?php echo $res[0]['last_name']?>">
						</div>
						<div class="form-group">
							<label for="">Mobile</label>
							<input type="text" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']?>">
						</div>
						<div class="form-group">
							<label for="">Alternate Mobile</label>
							<input type="text" class="form-control" name="alternate_mobile" value="<?php echo $res[0]['alternate_mobile']?>">
						</div>
						<div class="form-group">
							<label for="">Door No</label>
							<input type="text" class="form-control" name="door_no" value="<?php echo $res[0]['door_no']?>">
						</div>
						<div class="form-group">
							<label for="">Street Name</label>
							<input type="text" class="form-control" name="street_name" value="<?php echo $res[0]['street_name']?>">
						</div>
						<div class="form-group">
							<label for="">City</label>
							<input type="text" class="form-control" name="city" value="<?php echo $res[0]['city']?>">
						</div>
						<div class="form-group">
							<label for="">Pincode</label>
							<input type="text" class="form-control" name="pincode" value="<?php echo $res[0]['pincode']?>">
						</div>
						<div class="form-group">
							<label for="">State</label>
							<input type="text" class="form-control" name="state" value="<?php echo $res[0]['state']?>">
						</div>
						<div class="form-group">
							<label for="">Landmark</label>
							<input type="text" class="form-control" name="landmark" value="<?php echo $res[0]['landmark']?>">
						</div>
            </div><!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary" id="submit_btn" name="btnEdit">Update</button>

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
            <h3 class="box-title">users</h3>
          </div>
          <div class="box-body table-responsive">
            <table class="table table-hover" data-toggle="table" id="users" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-trim-on-search="false" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-y') ?>",
                            "ignoreColumn": ["state"]   
                        }'>
              <thead>
                <tr>
                  <th data-field="state" data-radio="true"></th>
                  <th data-field="id" data-sortable="true">ID</th>
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
  <script>
  $('#users').on('check.bs.table', function(e, row) {
    $('#details').val(row.id + " | " + row.mobile);
    $('#user_id').val(row.id); // Update 'user_id' with the selected user's id
  });
</script>
