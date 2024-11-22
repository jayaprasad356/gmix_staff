<section class="content-header">
    <h1>Orders /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-body table-responsive">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_filter">Filter by Date:</label>
                            <select id="date_filter" class="form-control">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                                <option value="">All</option>
                            </select>
                        </div>
                    </div>
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=orders" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="user_mobile" data-sortable="true">User Mobile</th>
                                    <th data-field="ordered_date" data-sortable="true">Ordered Date</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="payment_mode" data-sortable="true">Payment Mode</th>
                                    <th data-field="address" data-sortable="true">Address</th>
                                    <th data-field="product_name" data-sortable="true">Product Name</th>
                                    <th data-field="measurement" data-sortable="true">Measurement</th>
                                    <th data-field="total_price" data-sortable="true">Total Price</th>
                                    <th data-field="est_delivery_date" data-sortable="true">Est Delivery Date</th>
                                    <th data-field="live_tracking" data-sortable="true">Live Tracking</th>
                                    <th data-field="attempt1" data-sortable="true">Attempt 1</th>
                                    <th data-field="copy_live_tracking" data-sortable="false">Copy</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
            <script>
        $(document).ready(function() {
    // Event listener for the dynamically generated "Copy" buttons
    $(document).on('click', '.copy-btn', function() {
        // Get the payment link from the button's data-link attribute
        var live_tracking = $(this).attr('data-link');

        // Create a temporary input element to hold the payment link
        var tempInput = document.createElement("input");
        tempInput.value = live_tracking;
        document.body.appendChild(tempInput);

        // Select the text in the input and copy it to the clipboard
        tempInput.select();
        document.execCommand("copy");

        // Remove the temporary input element
        document.body.removeChild(tempInput);

        // Optional: Show a confirmation message
        alert("Live Tracking link copied: " + live_tracking);
    });
});

    </script>
        </div>
    </section>

<script>
    // Function to format the live_tracking column with a Copy button
   
    $('#date_filter').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "date_filter": $('#date_filter').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>
