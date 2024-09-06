
<section class="content-header">
    <h1>Addresses /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
     <a class="btn btn-block btn-default" href="add-addresses.php"><i class="fa fa-plus-square"></i> Add Addresses</a>
</ol>   
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=addresses" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th data-field="operate" data-events="actionEvents">Action</th>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true">User Name</th>
                                    <th data-field="first_name" data-sortable="true">First Name</th>
                                    <th data-field="last_name" data-sortable="true">Last Name</th>
                                    <th data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="alternate_mobile" data-sortable="true">Alternate Mobile</th>
                                    <th data-field="door_no" data-sortable="true">Door No</th>
                                    <th data-field="street_name" data-sortable="true">Street Name</th>
                                    <th data-field="city" data-sortable="true">City</th>
                                    <th data-field="pincode" data-sortable="true">Pincode</th>
                                    <th data-field="state" data-sortable="true">State</th>
                                    <th data-field="landmark" data-sortable="true">Landmark</th>
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
