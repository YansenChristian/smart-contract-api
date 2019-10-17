@extends('layout.admin')
@section('title')
    Order Management |
@stop

@section('breadcrumb')
    <ul class="page-breadcrumb" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
        xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
        <li><a href="{{ url('admin-cp/admin') }}"> Dashboard </a><i class="fa fa-angle-right"></i></li>
        <li class="active"> Incoming Order <i class="fa fa-angle-right"></i></li>
        <li><a href="{{ url('admin-cp/incoming-order/create') }}"> Order Create </a><i class="fa fa-angle-right"></i></li>
    </ul>
@endsection

@section('style')
    <link type="text/css" rel="stylesheet" href="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}" />

    <link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') !!}" rel="stylesheet" media="screen">
    <style>
        .custom-box{border: 2px solid #EEEEEE;}

        table.table-fixed {table-layout: fixed;}
        table.table-fixed td {overflow: hidden;padding:0px;}
        tfoot {display: table-header-group;}

        .width-80 {width : 80%;}

        .table-header {background-color: #EEEEEE;}

        .dataTables_wrapper .dataTables_paginate {float: right;text-align: right;padding-top: 0.25em;}
        .dataTables_wrapper .dataTables_filter {text-align: right;padding-top: 0.25em;display:none;}
        table.table-bordered tbody td {word-break: break-word;}
    </style>
@endsection

@section('content')

@if (Session::get('success'))
	<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
@elseif (Session::get('warning'))
	<div class="alert alert-warning" role="alert">{{ Session::get('warning') }}</div>
@endif
<div class="row">
    <div class="col-lg-12"><br><a href="{{ url('admin-cp/incoming-order/create') }}" class="btn green" role="button">Create Order</a></div>
</div>
<h3 class="page-title">Incoming Order</h3>
<div class="row no-margin">
    <div class="col-md-12 container-content" style="overflow:auto;">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead>
                <tr class="table-header">
                    <th>Order Serial</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Order Status</th>
                    <th>Shipping Status</th>
                    <th>Order Date</th>
                    <!-- <th>Due Date</th> -->
                    <th>On Event - Item(s)</th>
                    <!-- <th>Extend</th> -->
                    <th>Doc</th>
                    <th>Required Action</th>
                    <th>Total Transaction</th>
                    <th>Action</th>
                </tr>
                <tr class="search-col">
                    <td>Order Serial</td>
                    <td>Customer</td>
                    <td>Email</td>
                    <td>Order Status</td>
                    <td>Shipping Status</td>
                    <td>Order Date</td>
                    <!-- <td>Due Date</td> -->
                    <td>On Event - Item(s)</td>
                    <!-- <td>Extend</td> -->
                    <td>Doc</td>
                    <td>Required Action</td>
                    <td>Total Transaction</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
	</div>
</div>
@endsection

@section('script')
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/jquery.dataTables.columnFilter.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}"></script>
    <script type="text/javascript">
        $(".delete").click(function(){
            $('input.hidden_id').val($(this).attr('data-hidden'));
        });
        function del(id){
            $('input.hidden_id').val(id);
        }
        var oTable = $('#dataTable').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url("admin-cp/incoming-order/incoming-order-list") !!}',
            columns: [
                {data: 'order_serial', name: 'order_serial'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'shipping_status', name: 'rl_orders.status_delivery_order'},
                {data: 'order_date', name: 'order_date'},
                // {data: 'due_date', name: 'due_date'},
                {data: 'on_event', name: 'on_event'},
                // {data: 'extend_status', name: 'extend_status'},
                {data: 'doc', name: 'doc', searchable: false},
                {data: 'required_action', name: 'required_action'},
                {data: 'total_transaction', name: 'total_transaction', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "order": [[ 5, "desc" ]],
            orderCellsTop: true,
            "sDom": 'prt<"bottom"ip><"clear">'
        });
        //$('.dtpaginate').append($(".dataTables_paginate"));
        $('#dataTable .search-col td').each(function (i)
        {
            var title = $('#dataTable thead th').eq($(this).index()).text();
            // or just var title = $('#sample_3 thead th').text();
            if(title == 'Order Status'){
                var search = '<select class="form-control input-small select2me" placeholder="Select...">'+
                                '<option value="">Select..</option>'+
                                '<option value="Paid">Paid</option>'+
                                '<option value="Waiting For Payment">Waiting For Payment</option>'+
                                '<option value="Reject">Reject</option>'+
                            '</select>';
                $(this).html('');
                $(search).appendTo(this).on("change", function(){oTable.fnFilter($(this).val(),i)});
            }else if(title == 'Shipping Status'){
                 var search = '<select class="form-control input-small select2me" placeholder="Select...">'+
                                 '<option value="">Select..</option>'+
                                 '<option value="Completed">Completed</option>'+
                                 '<option value="Not yet completed">Not yet completed</option>'+
                             '</select>';
                 $(this).html('');
                 $(search).appendTo(this).on("change", function(){oTable.fnFilter($(this).val(),i)});
            }else if(title == 'Extend'){
                              var search = '<select class="form-control input-small select2me" placeholder="Select...">'+
                                              '<option value="">Select..</option>'+
                                              '<option value="Yes">Yes</option>'+
                                              '<option value="No">No</option>'+
                                          '</select>';
                              $(this).html('');
                              $(search).appendTo(this).on("change", function(){oTable.fnFilter($(this).val(),i)});
                         }else if(title == 'Action' || title == 'Total Transaction' || title == 'Doc' ){
                $(this).html('');
            }else{
                var search = '<input type="text" class="form-control" placeholder="Search ' + title + '" />';
                $(this).html('');
                $(search).appendTo(this).keyup(function(){oTable.fnFilter($(this).val(),i)})
            }
        });
    </script>
@endsection
