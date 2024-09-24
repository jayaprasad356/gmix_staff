<?php session_start();

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$function = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
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
$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
$yes_dt = date("Y-m-d 00:00:00", strtotime("yesterday"));
$yesterday = date("Y-m-d", strtotime("yesterday"));
$yes_dt_ = $yesterday . " " . date("H:i:s");
// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;
$function = new custom_functions;
include "header.php";
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Gmix Staff- Dashboard</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Home</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                </li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>
                                <?php
                                $staffID = $_SESSION['id'];
                                $date = date('Y-m-d');
                                $sql = "SELECT COUNT(o.id) AS count FROM orders o JOIN users u ON u.id = o.user_id WHERE u.staff_id = '$staffID' AND DATE(o.ordered_date) = '$date' AND o.status != 2";
                                $db->sql($sql);
                                $res = $db->getResult();
                                $count = isset($res[0]['count']) ? $res[0]['count'] : 0;
                                echo $count;
                                ?>
                            </h3>
                            <p>Today Orders</p>
                        </div>
                        <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3><?php 
                             ?>10</h3>
                            <p>Today Targets</p>
                        </div>
                        <div class="icon"><i class="fa fa-users"></i></div>
                        <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
             </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>