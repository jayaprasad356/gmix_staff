<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('./includes/variables.php');
include_once('includes/custom-functions.php');

$fn = new custom_functions;

// Database connection configuration
$host = 'localhost';
$username = 'u743445510_demo_gmix';
$password = 'Demogmix@2024';
$database = 'u743445510_demo_gmix';

// Create a new mysqli object
$db = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($db->connect_errno) {
    die("Failed to connect to MySQL: " . $db->connect_error);
}

if (isset($_POST['btnLogin'])) {
    // Get mobile and password
    $mobile = $db->real_escape_string($_POST['mobile']);
    $password = $db->real_escape_string($_POST['password']); // Still sanitize the password input

     // set time for session timeout
     $currentTime = time() + 25200;
     $expired = 3600;

    // Create array variable to handle errors
    $error = array();

    // Check whether $mobile is empty or not
    if (empty($mobile)) {
        $error['mobile'] = "*Mobile number should be filled.";
    }

    // Check whether $password is empty or not
    if (empty($password)) {
        $error['password'] = "*Password should be filled.";
    }

    // If mobile and password are not empty, check in the database
    if (!empty($mobile) && !empty($password)) {
        // Query to check the mobile number and plain-text password
        $sql = "SELECT * FROM staffs WHERE mobile = '$mobile' AND password = '$password'";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Set session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['timeout'] = $currentTime + $expired;

            // Redirect to home.php
            header("Location: home.php");
            exit(); // Make sure to call exit after header redirect
        } else {
            $error['failed'] = "<span class='label label-danger'>Invalid Mobile Number or Password!</span>";
        }
    }
}
?>

<?php echo isset($error['update_user']) ? $error['update_user'] : ''; ?>
<div class="col-md-4 col-md-offset-4 " style="margin-top:150px;">
    <!-- general form elements -->
    <div class='row'>
        <div class="col-md-12 text-center">
            <img src="dist/img/gmix.png" height="110">
            <h3>Gmix-Staff -Dashboard</h3>
        </div>
        <div class="box box-info col-md-12">
            <div class="box-header with-border">
                <h3 class="box-title">Staff Login</h3>
                <center>
                    <div class="msg"><?php echo isset($error['failed']) ? $error['failed'] : ''; ?></div>
                </center>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputMobile">Mobile Number :</label>
                        <input type="text" name="mobile" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword">Password :</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="btnLogin" class="btn btn-info pull-left">Login</button>
                    </div>
                </div>
            </form>
        </div><!-- /.box -->
    </div>
</div>
<?php include('footer.php'); ?>
