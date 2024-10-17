
<section class="content-header">
    <h1>Payment Links /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
     <a class="btn btn-block btn-default" href="create_payment_links.php"><i class="fa fa-plus-square"></i>Create Payment Links</a>
</ol>    
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=payment_links" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="user_name" data-sortable="true">User Name</th>
                                    <th data-field="user_mobile" data-sortable="true">User Mobile</th>
                                    <th data-field="payment_link" data-sortable="true">Payment Link</th>
                                    <th data-field="copy_payment_links" data-sortable="false">Copy</th>
                                </tr>
                            </thead>
                        </table>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
    // Event listener for the dynamically generated "Copy" buttons
    $(document).on('click', '.copy-btn', function() {
        // Get the payment link from the button's data-link attribute
        var paymentLink = $(this).attr('data-link');

        // Create a temporary input element to hold the payment link
        var tempInput = document.createElement("input");
        tempInput.value = paymentLink;
        document.body.appendChild(tempInput);

        // Select the text in the input and copy it to the clipboard
        tempInput.select();
        document.execCommand("copy");

        // Remove the temporary input element
        document.body.removeChild(tempInput);

        // Optional: Show a confirmation message
        alert("Payment link copied: " + paymentLink);
    });
});

    </script>
<script>

    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#trail_completed').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#referred_by').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#plan').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#profile').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    function queryParams(p) {
    return {
        "date": $('#date').val(),
        "seller_id": $('#seller_id').val(),
        "community": $('#community').val(),
        "status": $('#status').val(),
        "trail_completed": $('#trail_completed').val(),
        "referred_by": $('#referred_by').val(),
        "plan": $('#plan').val(),
        "profile": $('#profile').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

    
</script>
