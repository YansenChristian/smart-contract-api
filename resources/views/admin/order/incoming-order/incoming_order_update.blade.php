@extends('layout.admin')
@section('title')
    Order Management |
@stop
@section('content')
<style>
  .scrollDiv{
    overflow-y: auto;
    max-height: 200px;
  }
</style>

    <!-- BEGIN PAGE HEADER-->
          <div class="page-bar">

            {{--*/ $serial = str_replace('/','---',$row->order_serial) /*--}}
      
            <!--BEGIN BREADCRUMB-->
            <ul class="page-breadcrumb">
              <li>
                <a href="#">Order</a>
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
              <!-- <li>
                <a href="{{url('frontend/order-view')}}">View</a>
                <i class="fa fa-angle-right"></i>
              </li> -->
              <li class="active">Update</li>
            </ul>
            <!--END BREADCRUMB-->

          </div>
          <h3 class="page-title">
            Incoming Order
          </h3>
          <!-- END PAGE HEADER-->

          <div class="row">
            <div class="col-md-12">

              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-md-4 col-xs-4" >
                      <h5>Update Order</h5>
                    </div>
                  </div>
                </div>
                <div class="panel-body">

                  {{--*/ $url = str_replace('/','---',$row->order_serial) /*--}}
                  <input type="hidden" id="no_order" value="{{$row->order_serial}}">
                  <div class="row">
                    <div class="col-md-2 col-xs-2"> 
                      <h5>Item Detail</h5>
                    </div>
                    <div class="col-md-2 col-xs-2 col-md-offset-8 col-xs-offset-8">
                      <a href="{{url('admin-cp/incoming-order/add-product/'.$url)}}" class="btn btn-default">Add Product</a>
                    </div>
                  </div>

                  <br />

                  <div class="row">
                    <div class="col-md-12">
                      <table class="table table-bordered">
                        <thead class="bg-grey">
                          <tr>
                            <td rowspan="2">Product</td>
                            <td rowspan="2">Price (Rp)</td>
                            <td rowspan="2">Qty</td>
                            <td colspan="2"span>Discount</td>
                            <td rowspan="2">Subtotal (Rp)</td>
                          </tr>
                          <tr>
                            <td>%</td>
                            <td>Rp</td>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($row->orderDetails as $key => $orderDetail)
                          <tr class="tr-order-detail">
                            <td>{{ $orderDetail->item_name }}</td>
                            <td> {{ displayNumericWithoutRp($orderDetail->price) }}
                            </td>
                            <td>
                              <input type="text" value="{{ $orderDetail->product_quantity }}" class="form-control qty-order-detail" /> 
                              <input type="hidden" value="{{ $orderDetail->id }}" class="form-control id-order-detail" /> 
                            </td>
                            <td>-</td>
                            <td>
                            @if($orderDetail->discount != null)
                              {{  displayNumericWithoutRp($orderDetail->discount) }}
                              {{--*/ $totalDetailOrder =  $orderDetail->discount * @$orderDetail->product_quantity /*--}}
                              <input type="hidden" value="{{ $orderDetail->discount }}" class="price" />
                            
                            @else
                              -
                              {{--*/ $totalDetailOrder =  $orderDetail->price*$orderDetail->product_quantity /*--}}
                              <input type="hidden" value="{{ $orderDetail->price }}" class="price" />
                            
                            @endif
                            
                            <input type="hidden" value="{{ $orderDetail->discount }}" class="form-control discount-order-detail" />
                            </td>
                            <td><span class='subtotal'>{{ displayNumericWithoutRp($totalDetailOrder) }}</span> </td>
                            
                          </tr>
                        @endforeach
                          
                        </tbody>
                        <tfoot class="bg-grey">
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td><strong><span class="grandTotal">{{ displayNumeric($row->total_price) }} </span><input type="hidden" id="grand_total" value="{{ $row->grand_total }}"/> </strong></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>

                  <br />

                  <div class="row">
                    <!--
                    <div class="col-md-6 col-xs-6">
                      <div class="panel panel-default">
                        <div class="panel-heading bg-grey">
                          <strong>Apply Promo / Voucher Code</strong>
                        </div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-9 col-xs-9">
                              <input type="text" id="promo_code" class="form-control" />
                            </div>
                            <div class="col-md-3 col-xs-3">
                              <button class="btn btn-default submit-promo" role="button">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    -->
                    <div class="col-md-6 col-xs-6">
                      <div class="panel panel-default">
                        <div class="panel-heading bg-grey">
                          <strong>Payment Method</strong>
                        </div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-9 col-xs-9">
                              <button class="btn btn-default notif-payment-method" role="button">Change Payment Method</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>

                  <br />


                </div>
                <div class="panel-footer">
                <!--
                  <button class="btn green" role="button">Submit</button>
                --> 
                  <a href="{{url('admin-cp/incoming-order/view/'.$url)}}" class="btn btn-default">Back</a>
                </div>
              </div>
            </div>


          </div>

@stop
@section('style')
<!-- BEGIN PAGE LEVEL STYLES -->

<link href="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-summernote/summernote.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/admin/pages/css/tasks.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/global/css/custom.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') !!}" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->
<link href="{!! asset('assets/admin/metronic/global/css/cropper.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admin/metronic/global/css/main.css') !!}" rel="stylesheet" type="text/css"/>
<link href="{!! asset('assets/admsin/metronic/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') !!}" rel="stylesheet" type="text/css"/>
@stop
@section('script')
<script src="{!! asset('assets/admin/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/admin/metronic/global/plugins/datatables/jquery.dataTables.columnFilter.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}" type="text/javascript"></script>
<script src="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}" type="text/javascript"></script>

    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <script src="{!! asset('assets/admin/metronic/global/scripts/metronic.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/layout/scripts/layout.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/layout/scripts/quick-sidebar.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/admin/layout/scripts/demo.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/admin/metronic/global/scripts/table-editable-reuseable.js') !!}"></script>
    <script src="{!! asset('assets/admin/metronic/admin/scripts/script_custom.js') !!}"></script>

    <script src="{!! asset('assets/js/frontend/incoming-order-cms-script.js') !!}"></script>
    <script type="text/javascript">
      var admin_url = function(suffix) {
        suffix = (typeof suffix !== "undefined") ? suffix : "";
        return "{{ url('admin-cp') }}/" + suffix;
      };
      var url = function(suffix) {
        suffix = (typeof suffix !== "undefined") ? suffix : "";
        return "{{ url('') }}/" + suffix;
      };
      var admin_asset = function(suffix) {
        return "{{ asset('admin-cp') }}/" + suffix;
      };
      var asset = function(suffix) {
        return "{{ asset('') }}/" + suffix;
      };
    </script>

    <script>
    $(document).ready(function() {
       var datepicker = $.fn.datepicker.noConflict();
       $.fn.bootstrapDP = datepicker;
     });

      jQuery(document).ready(function() {
          Metronic.init(); // init metronic core components
          //Layout.init(); // init current layout
          QuickSidebar.init(); // init quick sidebar
          Demo.init(); // init demo features
//                TableEditable.init();
        });
    </script>
@stop