<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['name'])) {
    header("location:index.php");
}


// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$currentdate = date('Y-m-d');
$db->connect();

        // Get the current date and time
        $date = new DateTime('now');

        // Round off to the nearest hour
        $date->modify('+' . (60 - $date->format('i')) . ' minutes');
        $date->setTime($date->format('H'), 0, 0);
    
        // Format the date and time as a string
        $date_string = $date->format('Y-m-d H:i:s');
        $currentdate = date('Y-m-d');

     

        //users
        if (isset($_GET['table']) && $_GET['table'] == 'users') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
            if (isset($_GET['offset'])) {
                $offset = $db->escapeString($_GET['offset']);
            }
            if (isset($_GET['limit'])) {
                $limit = $db->escapeString($_GET['limit']);
            }
            if (isset($_GET['sort'])) {
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])) {
                $order = $db->escapeString($_GET['order']);
            }
        
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($fn->xss_clean($_GET['search']));
                $where .= " AND (mobile LIKE '%" . $search . "%') ";
            }
            if (isset($_GET['sort'])) {
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])) {
                $order = $db->escapeString($_GET['order']);
            }
        
            $sql = "SELECT COUNT(*) as total FROM `users` WHERE staff_id = 0" . $where;
            $db->sql($sql);
            $res = $db->getResult();
            $total = $res[0]['total'];
           
            $sql = "SELECT * FROM users WHERE staff_id = 0" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();

              
        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
        
                
               // $operate = ' <a href="edit-users.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate = ' <a class="text text-danger" href="delete-users.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $take = '<a href="assign-users.php?id=' . $row['id'] . '" class="btn btn-success">Take</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['take'] = $take;
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }
        //plan
        if (isset($_GET['table']) && $_GET['table'] == 'my_customers') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
            if (isset($_GET['offset']))
            $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
            $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
            $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
            $order = $db->escapeString($_GET['order']);

             if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($fn->xss_clean($_GET['search']));
                $where .= " AND (mobile LIKE '%" . $search . "%') ";
            }
            if (isset($_GET['sort'])){
            $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
            $order = $db->escapeString($_GET['order']);
            }
            if (!isset($_SESSION['id'])) {
            // Redirect to login page or handle unauthorized access
            }
            $sql = "SELECT COUNT(`id`) as total FROM `users` WHERE staff_id = {$_SESSION['id']} " . $where;
            $db->sql($sql);
            $res = $db->getResult();
            $total = $res[0]['total'];

            $sql = "SELECT id, mobile FROM users WHERE staff_id = {$_SESSION['id']} " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();

            $bulkData = array();
            $bulkData['total'] = $total;

            $rows = array();
            $tempRow = array();

            foreach ($res as $row) {
            $operate = ' <a class="text text-danger" href="delete-customers.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
            $tempRow['id'] = $row['id'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

        if (isset($_GET['table']) && $_GET['table'] == 'orders') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
            $date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
        
            if (isset($_GET['offset'])) {
                $offset = $db->escapeString($_GET['offset']);
            }
            if (isset($_GET['limit'])) {
                $limit = $db->escapeString($_GET['limit']);
            }
            if (isset($_GET['sort'])) {
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])) {
                $order = $db->escapeString($_GET['order']);
            }
        
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($fn->xss_clean($_GET['search']));
                $where .= " AND (users.name LIKE '%" . $search . "%' OR products.name LIKE '%" . $search . "%')";
            }
        
            // Add date filter logic
            if ($date_filter == 'today') {
                $where .= " AND DATE(orders.ordered_date) = CURDATE()";
            } elseif ($date_filter == 'yesterday') {
                $where .= " AND DATE(orders.ordered_date) = CURDATE() - INTERVAL 1 DAY";
            }
        
            if (!isset($_SESSION['id'])) {
                // Redirect to login page or handle unauthorized access
            }
        
            // Query for total count
            $sql = "SELECT COUNT(orders.id) as total
                    FROM orders
                    INNER JOIN users ON orders.user_id = users.id
                    INNER JOIN products ON orders.product_id = products.id
                    INNER JOIN addresses ON orders.address_id = addresses.id
                    WHERE users.staff_id = {$_SESSION['id']}" . $where;
            $db->sql($sql);
            $res = $db->getResult();
            $total = $res[0]['total'];
        
            // Query for paginated results
            $sql = "SELECT orders.id, users.name as user_name, products.name as product_name,
                           CONCAT(products.measurement, products.unit) as measurement,
                           CONCAT(addresses.door_no, ', ', addresses.street_name, ', ', addresses.state, ', ', addresses.city, ', ', addresses.pincode) as address,
                           orders.status, orders.ordered_date, orders.total_price, orders.est_delivery_date,
                           CONCAT(orders.live_tracking, orders.awb) as live_tracking
                    FROM orders
                    INNER JOIN users ON orders.user_id = users.id
                    INNER JOIN products ON orders.product_id = products.id
                    INNER JOIN addresses ON orders.address_id = addresses.id
                    WHERE users.staff_id = {$_SESSION['id']}" . $where . "
                    ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
        
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
                $operate = ' <a class="text text-danger" href="delete-customers.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['user_name'] = $row['user_name'];
                $tempRow['product_name'] = $row['product_name'];
                $tempRow['measurement'] = $row['measurement'];
                $tempRow['address'] = $row['address'];
                $tempRow['ordered_date'] = $row['ordered_date'];
                $tempRow['total_price'] = $row['total_price'];
                $tempRow['est_delivery_date'] = $row['est_delivery_date'];
                if ($row['status'] == 0) {
                    $tempRow['status'] = "<p class='label label-default'>Wait For Confirmation</p>";
                } elseif ($row['status'] == 1) {
                    $tempRow['status'] = "<p class='label label-success'>Confirmed</p>";
                } elseif ($row['status'] == 2) {
                    $tempRow['status'] = "<p class='label label-danger'>Cancelled</p>";
                } elseif ($row['status'] == 3) {
                    $tempRow['status'] = "<p class='label label-primary'>Shipped</p>";
                } elseif ($row['status'] == 4) {
                    $tempRow['status'] = "<p class='label label-info'>Delivered</p>";
                } else {
                    $tempRow['status'] = "<p class='label label-default'>Unknown</p>";
                }
                $tempRow['live_tracking'] = $row['live_tracking'];
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }
        if (isset($_GET['table']) && $_GET['table'] == 'addresses') {
            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
        
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($_GET['search']);
                $where .= " AND (l.id LIKE '%" . $search . "%' OR l.first_name LIKE '%" . $search . "%' OR l.last_name LIKE '%" . $search . "%' OR l.mobile LIKE '%" . $search . "%' OR l.alternate_mobile LIKE '%" . $search . "%' OR l.door_no LIKE '%" . $search . "%' OR l.street_name LIKE '%" . $search . "%' OR l.city LIKE '%" . $search . "%' OR l.pincode LIKE '%" . $search . "%' OR l.state LIKE '%" . $search . "%' OR l.landmark LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%')";
            }
        
            $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;
        
            $sql = "SELECT COUNT(l.id) AS total FROM `addresses` l " . $join;
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row) {
                $total = $row['total'];
            }
        
            $sql = "SELECT l.id AS id, l.*, u.name FROM `addresses` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
            $db->sql($sql);
            $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
        
            $rows = array();
        
            foreach ($res as $row) {
        
                $operate = '<a href="edit-addresses.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-addresses.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        
                $tempRow = array();
                $tempRow['id'] = $row['id'];
                $tempRow['name'] = $row['name'];
                $tempRow['first_name'] = $row['first_name'];
                $tempRow['last_name'] = $row['last_name'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['alternate_mobile'] = $row['alternate_mobile'];
                $tempRow['door_no'] = $row['door_no'];
                $tempRow['street_name'] = $row['street_name'];
                $tempRow['city'] = $row['city'];
                $tempRow['pincode'] = $row['pincode'];
                $tempRow['state'] = $row['state'];
                $tempRow['landmark'] = $row['landmark'];
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }
      
$db->disconnect();

