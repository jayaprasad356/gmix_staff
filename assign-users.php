<?php
session_start();
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once('includes/crud.php');
$db = new Database();
$db->connect();
// start session

// set time for session timeout
$currentTime = time() + 25200;
$expired = 720000;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
   
    
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

if (isset($_POST['btnYes'])) {
    if (isset($_GET['id'])) {
        $ID = $db->escapeString(htmlspecialchars($_GET['id']));
    } else {
        header("Location: users.php");
        exit();
    }

    if (!isset($_SESSION['id'])) {
        header("Location: users.php");
        exit();
    }

    $staffID = $_SESSION['id'];
    $sql_query = "UPDATE users SET staff_id = '$staffID' WHERE id = '$ID'";
    $db->sql($sql_query);
    $update_result = $db->getResult();

    header("Location: users.php"); 
    exit();
}

if (isset($_POST['btnNo'])) {
header("location: users.php");
}

?>
?>


<?php include "header.php"; ?>
<html>

<head>
    <title>Assign Users | - Dashboard</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
	
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	    <h1>Confirm Action</h1>
		<hr />
		<form method="post">
			<p>Do you want to take this user ?</p>
			<input type="submit" class="btn btn-primary" value="Yes" name="btnYes" />
			<input type="submit" class="btn btn-danger" value="No" name="btnNo" />
		</form>
    </div><!-- /.content-wrapper -->
</body>

</html>
<?php include "footer.php"; ?>