<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
?>
<?php
if (isset($_POST['btnAdd'])) {
    $order_id = $db->escapeString($_POST['order_id']);
    $title = $db->escapeString($_POST['title']);
    $description = $db->escapeString($_POST['description']);

    if (empty($order_id)) {
        $error['order_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($title)) {
        $error['title'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($description)) {
        $error['description'] = " <span class='label label-danger'>Required!</span>";
    }

    // Only proceed if there are no errors
    if (!empty($order_id) && !empty($title) && !empty($description)) {

        $sql_query = "INSERT INTO tickets (order_id, title, description,status)
        VALUES ('$order_id', '$title', '$description', 0)";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }
        if ($result == 1) {
            header("Location: add-tickets.php?status=success");
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
    <h1>Add Tickets <small><a href='tickets.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Tickets</a></small></h1>
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<section class='content-header'>
                <span class='label label-success'>Tickets Added Successfully</span>
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
        <!-- Users List -->
        <div class="col-md-6 col-xs-12 order-md-2 order-xs-1">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Orders</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="users" data-url="api-firebase/get-bootstrap-table-data.php?table=orders_list" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-trim-on-search="false" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "users-list-<?= date('d-m-y') ?>",
                        "ignoreColumn": ["state"]
                    }'>
                        <thead>
                        <tr>
                            <th data-field="state" data-radio="true"></th>
                            <th data-field="id" data-sortable="true">Order ID</th>
                            <th data-field="addresses_mobile" data-sortable="true">Addresses Mobile</th>
                            <th data-field="address" data-sortable="true">Address</th>
                            <th data-field="product_details" data-sortable="true">Product Details</th>
                            <th data-field="total_price" data-sortable="true">Total Price</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xs-12 order-md-1 order-xs-2">
            <div class="box box-primary">
            <div class="box-header with-border"></div>
            <!-- /.box-header -->
            <!-- form start -->
            <form name="add_project_form" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <div class="form-group">
                    <label for="">Orders</label>
                    <textarea id="details" name="order_id" class="form-control" rows="4" readonly></textarea>
                    <input type="hidden" id="order_id" name="order_id" value="">
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"></textarea>
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
        </div><!-- /.row -->
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // When a user is selected from the table, fill in the details
        $('#users').on('check.bs.table', function (e, row) {
        // Set the 'details' field with the selected order's details
        // Replace <br> tags with newlines in product details
        var productDetails = row.product_details.replace(/<br>/g, '\n');

        $('#details').val(
            'Order ID: ' + row.id + '\n' +
            'Addresses Mobile: ' + row.addresses_mobile + '\n' +
            'Address: ' + row.address + '\n' +
            'Product Details: ' + productDetails + '\n' +
            'Total Price: ' + row.total_price
        );

        // Set the 'order_id' hidden field with the selected order's ID
        $('#order_id').val(row.id); // Set the 'order_id' with the actual order ID from the selected row
        });
    </script>

