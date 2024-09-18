<section class="content-header">
    <h1>COD Orders /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-body table-responsive">
                   
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=cod_orders" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="ordered_date" data-sortable="true">Ordered Date</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="address" data-sortable="true">Address</th>
                                    <th data-field="product_name" data-sortable="true">Product Name</th>
                                    <th data-field="measurement" data-sortable="true">Measurement</th>
                                    <th data-field="total_price" data-sortable="true">Total Price</th>
                                    <th data-field="est_delivery_date" data-sortable="true">Est Delivery Date</th>
                                    <th data-field="live_tracking" data-sortable="true" data-formatter="liveTrackingFormatter">Live Tracking</th>
                                    <th data-field="chat_conversation">Chat Conversation</th>
                                    <th data-field="payment_image">Payment Image</th>
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
    // Function to format the live_tracking column with a Copy button
    function liveTrackingFormatter(value, row, index) {
        return `<div class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-success" onclick="copyToClipboard('${value}')">Copy</button>
                    </span>
                </div>`;
    }

    // Function to copy text to clipboard
    function copyToClipboard(text) {
        var tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Copied to clipboard!');
    }

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
