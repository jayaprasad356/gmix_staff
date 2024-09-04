<section class="content-header">
    <h1>Create Orders /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <form id="customer_form" method="POST" action="#" class="form-horizontal">

                        <div class="form-group">
                            <label for="mobile" class="col-sm-2 control-label">Mobile Number:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" required>
                            </div>

                            <div class="col-sm-4">
                                <button type="button" class="btn btn-primary" id="checkMobile">Submit</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="first_name" class="col-sm-2 control-label">First Name:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                            </div>

                            <label for="last_name" class="col-sm-2 control-label">Last Name:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alternate_mobile" class="col-sm-2 control-label">Alternate Mobile Number:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="alternate_mobile" name="alternate_mobile" placeholder="Enter alternate mobile number">
                            </div>
                            <label for="door_no" class="col-sm-2 control-label">Door No:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="door_no" name="door_no" placeholder="Enter door number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="street_name" class="col-sm-2 control-label">Street Name:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="street_name" name="street_name" placeholder="Enter street name">
                            </div>
                            <label for="city" class="col-sm-2 control-label">City:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="city" name="city" placeholder="Enter city">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pincode" class="col-sm-2 control-label">Pincode:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter pincode">
                            </div>
                            <label for="state" class="col-sm-2 control-label">State:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="state" name="state" placeholder="Enter state">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="landmark" class="col-sm-2 control-label">Landmark:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Enter landmark">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product_id" class="col-sm-2 control-label">Select Product:</label><i class="text-danger asterik"></i>
                            <div class="col-sm-4">
                                <select id='product_id' name="product_id" class='form-control'>
                                    <?php
                                    // Fetch products with name, price, and measurement unit
                                    $sql = "SELECT id, name, price, measurement,unit FROM `products`";
                                    $db->sql($sql);
                                    $result = $db->getResult();
                                    foreach ($result as $value) {
                                        echo "<option value='{$value['id']}'>{$value['name']} - {$value['price']} - {$value['measurement']}{$value['unit']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
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
        $('#checkMobile').click(function() {
            var mobile = $('#mobile').val();

            if (mobile) {
                $.ajax({
                    url: 'check_mobile.php',
                    type: 'POST',
                    data: { mobile: mobile },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status !== 'not found') {
                            $('#first_name').val(data.first_name);
                            $('#last_name').val(data.last_name);
                            $('#alternate_mobile').val(data.alternate_mobile);
                            $('#door_no').val(data.door_no);
                            $('#street_name').val(data.street_name);
                            $('#city').val(data.city);
                            $('#pincode').val(data.pincode);
                            $('#state').val(data.state);
                            $('#landmark').val(data.landmark);
                        } else {
                            alert('No data found for this mobile number.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            } else {
                alert('Please enter a mobile number.');
            }
        });
    });
</script>
