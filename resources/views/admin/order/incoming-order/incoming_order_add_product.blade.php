@extends('layout.admin')
@section('title')
    Order Management |
@stop
@section('breadcrumb')
<!--BEGIN BREADCRUMB-->
    <ul class="page-breadcrumb">

      {{--*/ $serial = str_replace('/','---',$row->order_serial) /*--}}
        <li>
            <a href="index.php">Order</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{url('admin-cp/incoming-order')}}">Order Management</a>
             <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{url('admin-cp/incoming-order/view/'.$serial)}}">View</a>
             <i class="fa fa-angle-right"></i>
        </li>
        <li>
          <a href="{{url('admin-cp/incoming-order/update-order/'.$serial)}}">Update</a>
             <i class="fa fa-angle-right"></i>
        </li>
        <li class="active">Add Product</li>
    </ul>
<!--END BREADCRUMB-->
@stop
@section('content')
<style>
    .custom-box{border: 2px solid #EEEEEE;}

    table.table-fixed {table-layout: fixed;}
    table.table-fixed td {overflow: hidden;padding:0px;}
    tfoot {display: table-header-group;}
    .width-80 {width : 80%;}
    .dataTables_wrapper .dataTables_paginate {float: right;text-align: right;padding-top: 0.25em;}
    .dataTables_wrapper .dataTables_filter {text-align: right;padding-top: 0.25em;display:none;}
    .scrollDiv{
        overflow-y: auto;
        max-height: 200px;
    }
</style>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        Incoming Order
    </h3>
    <!-- END PAGE HEADER-->
      
    {{--*/ $url = str_replace('/','---',$row->order_serial) /*--}}
    <div class="row">
      <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                  <div class="col-md-4 col-xs-4" >
                      <h4>Add Product</h4>
                  </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                              <td class="col-md-6">Category</td>
                              <td class="col-md-6">Product</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td>
                                {!! Form::select('category', array('' => '') + $categories, '', ['class'=>'form-control input small select2me', 'id' => 'category', 'data-placeholder'=>'Select..']) !!}
                                {!! Form::hidden('cat_id', '' ,['id' => 'cat-id']) !!}
                                <div id="sub-category-list"></div>
                              </td>
                              <td>
                                <input type="text" value="" name="search" class="form-control searchInput" placeholder="Search product..." id="item">{!! Form::hidden('cat_id', '' ,['id' => 'cat-id']) !!}
                                  {!! Form::hidden('item_id', '' ,['id' => 'item-id']) !!}
                              </td>
                            </tr>
                        </tbody>
                      </table>
                  </div> 
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <button class="btn btn-default" id="btnSearch" onclick="searchDataFunc(1)" role="button">Search</button>
                      <p class="text-danger error"><small></small></p>
                  </div>
                </div>
                <form method='post' action='{{action("Admin\Order\IncomingOrderController@postAddProduct")}}'>
                  <input type="hidden" value="{{$row->id}}" name="id">
                  <input type="hidden" value="{{$row->order_serial}}" name="order_serial">
                    <div class="row" id="searchResult">
                      <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12" id="title"></div>
                          </div>
                          <br/>
                          <br/>
                        <div id="search"></div>
                    </div>
                </div>
                <div id="paging_product"></div>
                  </div>
              </div>
            </div>
            <div class="panel-footer">
              <input type="submit" class="btn green" style="background-color: #38B24A;" value="Add Product">
              <a href="../view/{{$row->id}}" class="btn default" style="background-color: #43455A;color:white;">Back</a>
            </div>
          </div>
      </div>
      </form>
    </div>


@stop


@section('style')
    <link href="{!! asset('assets/admin/metronic/global/css/cropper.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/admin/metronic/global/css/main.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/admin/metronic/global/css/custom.css') !!}" rel="stylesheet" type="text/css"/>
    <link type="text/css" rel="stylesheet" href="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}" />
    <link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') !!}" rel="stylesheet" media="screen" />
    <link href="{!! asset('css/frontend/jquery-ui.css') !!}" rel="stylesheet" />
@stop

@section('jsscript')
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features
    TableEditable.init();
@stop

@section('script')
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/select2/select2.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/jquery.dataTables.columnFilter.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{!! asset('assets/admin/metronic/global/scripts/metronic.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/layout/scripts/layout.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/layout/scripts/quick-sidebar.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/layout/scripts/demo.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/pages/scripts/table-editable.js') !!}"></script>
    <script src="{!! asset('assets/js/frontend/jquery-ui-auto.js') !!}"></script>
    <script src="{!! cdn('assets/js/frontend/jquery.simplePagination.js') !!}"></script>
  
    <script>
    var parentArr = [];

    function showChild(parent_id){

          $('#cat-id').val(parent_id);
      $.ajax({
        url:"{{action('Item\CategoryController@postGetChild')}}",
        type:"post",
        async:false,
        data:'parent_id='+parent_id,
        success:function(result){

          $('.child-of-'+parent_id).remove();
          // parentArr.push(parent_id);

          if(result.status == 'success'){

            var children = result.result;
            var html = "";
            
            //show child
            if(children != ""){

                html += '<div class="col-md-11 col-xs-11" id="child-of-'+parent_id+'">'+
                '<select class="form-control select-child category-cache" name="category" placeholder="Select.." onchange="showChild(this.value)">';
              
                html += '<option value=""></option>';

                children.forEach(function(row){
                  if(row.category){
                      html += '<option value="'+row.category.id+'">'+row.category.name+'</option>';
              }
              });
              html += '</select></div>';
                
              $('#sub-category-list').append(html);
              $('#child-of-'+parent_id).find('.select-child').select2();

        //       console.log(parentArr);
        //       $.each( parentArr, function( key, value ) {
        //          $('#child-of-'+parent_id).addClass('child-of-'+value);
          // });
            
            }

          }
          else{
            console.log(result);
          }

        }
      })
    }

      function catChangeFunc()
      {
        var data = $('#category').val();
        $.ajax({
          url : "{{action('Admin\QuotationController@getProductAjax')}}",
          data: 'data='+data,
          type : "GET",
          success : function(result)
          { 
            $('#product').html('');
            $('#product').html('<option value=""></option>');
            var i=0;
            result.result.forEach(function(datum){
              $('#product').append('<option value="'+datum[0].id+'">'+datum[0].name+'</option>');
            });
          }
        });
      }

      function loadingProduct(data){
        var append = "<div class='row'>";
        var i=1;
        var checkboxPrice = '';
        data.forEach(function(datum){
          if(datum != null){
              if(datum.price == null){
                checkboxPrice = 'disabled=""';
              }

              append += ''+
              '<div class="col-md-4 col-xs-4">'+
                      '<div class="panel panel-default">'+
                        '<div class="panel-body">'+
                          '<div class="row">'+
                            '<div class="col-md-1 col-xs-1">'+
                              '<input type="checkbox" '+checkboxPrice+' class="checkboxes" name="addProduct['+datum.item_id+']" value=\''+JSON.stringify(datum)+'\' class="form-control" />'+
                            '</div>'+
                            '<div class="col-md-5 col-xs-5">'+
                              '<span>Vendor</span>'+
                            '</div>'+
                            '<div class="col-md-4 col-xs-4">'+
                              '<span>'+datum.vendor_name+'</span>'+
                            '</div>'+
                          '</div>'+
                          '<hr class="col-md-offset-1 col-xs-offset-1">'+
                          '<div class="row">'+
                            '<div class="col-md-5 col-xs-5 col-md-offset-1 col-xs-offset-1">'+
                              '<span>Name</span>'+
                            '</div>'+
                            '<div class="col-md-4 col-xs-4">'+
                              '<span>'+datum.item_name+'</span>'+
                            '</div>'+
                          '</div>'+
                          '<hr class="col-md-offset-1 col-xs-offset-1">'+
                          '<div class="row">'+
                            '<div class="col-md-5 col-xs-5 col-md-offset-1 col-xs-offset-1">'+
                              '<span>Unit Price</span>'+
                            '</div>'+
                            '<div class="col-md-4 col-xs-4">'+
                              '<span>'+displayNumeric(datum.price)+'</span>'+
                            '</div>'+
                          '</div>'+
                          '<hr class="col-md-offset-1 col-xs-offset-1">'+
                          '<div class="row">'+
                            '<div class="col-md-5 col-xs-5 col-md-offset-1 col-xs-offset-1">'+
                              '<span>Stock</span>'+
                            '</div>'+
                            '<div class="col-md-4 col-xs-4">'+
                              '<span>'+datum.stock+'</span>'+
                            '</div>'+
                          '</div>'+
                          '<hr class="col-md-offset-1 col-xs-offset-1">'+
                          '<div class="row">'+
                            '<div class="col-md-5 col-xs-5 col-md-offset-1 col-xs-offset-1">'+
                              '<span>Quantity</span>'+
                            '</div>'+
                            '<div class="col-md-4 col-xs-4">'+
                              '<input type="number" disabled="" name="quantity['+datum.item_id+']" class="form-control qty" min="0" max="'+datum.item_id+'" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                    '</div>'+
                    '</div>'; 
                    if(i%3==0)
              {
                append+='</div><div class="row">';
              }
              i++;
            }
        });
        append+='</div>';

        return append;
      }

      function searchDataFunc(page)
      {
        cat_id = $('#cat-id').val();
        item_id = $('#item-id').val();
        $('#item-id').val('');

        $.ajax({
          url : "{{action('Admin\QuotationController@getConstraintData')}}",
          data: 'cat='+cat_id+'&prod='+item_id+'&page='+page,
          type : "GET",
          success : function(result)
          { 
            $('#search').html('');
            if(result.status=='success')
            {
              var page_count = result.result.page_count;
              var total_product = result.result.total_record;
              console.log(page_count);
              var data = result.result.data;

              var append = loadingProduct(data);
              var paging = '';

              if(page_count > 1){  
                paging = '<input type="hidden" id="total_product" value="'+total_product+'">'+
                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right no-padding">'+
                            '<div class="page-nav">'+
                                '<ul id="listProductPagination" class="pagination pull-right">'+
                                '</ul>'+
                            '</div>'+
                        '</div>';
              }

            //$('#total_product').val(total_product);
            $('#search').html(append);
            $('#paging_product').html(paging);
            $('.pagination#listProductPagination').pagination({
                items: $('#total_product').val(),
                itemsOnPage: 15,
                onPageClick: function(pageNumber, event){
                    
                    event.preventDefault();
                    searchDataFuncOnly(pageNumber);
                },
            });

            $('.checkboxes').change(function() {
                            if($(this).is(":checked")){
                                // console.log('masuk');
                                $(this).closest('.panel-body').find('.qty').prop('disabled',false);
                                $(this).closest('.panel-body').find('.qty').prop('required',true);
                            }
                            else{
                                $(this).closest('.panel-body').find('.qty').val('').prop('disabled',true);
                          }                                          
                        });
            }
          }
        });
      }

      function searchDataFuncOnly(page)
      {
        cat_id = $('#cat-id').val();
        item_id = $('#item-id').val();
        $('#item-id').val('');

        $.ajax({
          url : "{{action('Admin\QuotationController@getConstraintData')}}",
          data: 'cat='+cat_id+'&prod='+item_id+'&page='+page,
          type : "GET",
          success : function(result)
          { 
            $('#search').html('');
            if(result.status=='success')
            {
              var page_count = result.result.page_count;
              var total_product = result.result.total_record;
              console.log(page_count);
              var data = result.result.data;

              var append = loadingProduct(data);
              $('#search').html(append);
              $('.checkboxes').change(function() {
                            if($(this).is(":checked")){
                                // console.log('masuk');
                                $(this).closest('.panel-body').find('.qty').prop('disabled',false);
                                $(this).closest('.panel-body').find('.qty').prop('required',true);
                            }
                            else{
                                $(this).closest('.panel-body').find('.qty').val('').prop('disabled',true);
                            }                                          
                        });
            
            }
          }
        });
      }
      
        jQuery(document).ready(function() {
          $('#product').val('');
          $('#searchResult').hide();
          $('#btnSearch').on('click',function() {
              $('#searchResult').show();
          });

          // $( "#item" ).autocomplete({
          //     delay: 0,
          //     source: asset('poc/autocomplete'),
          //     select:function(event,ui){
          //         //console.log(ui);
          //         // var val = ui.item.value.replaceAll("/", " ");
          //         // window.location.replace($('#base-url-kelvin').val()+'/search/'+val+'/1');
          //     }
          // });
          $( "#item" ).autocomplete({
            source: function( request, response ) {
              $.ajax({
                url: asset('admin-cp/quotation/list-product'),
                data: {
                  name: request.term,
                  cat: $('#cat-id').val()
                },
                success: function( data ) {
                  response( data );
                }
              });
            },
            select: function( event, ui ) {
              $('#item-id').val(ui.item.id);
          }
        });

      $('#category').on('change', function(){
        $('#sub-category-list').empty();
        showChild($(this).val());
      });
        });
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
@stop