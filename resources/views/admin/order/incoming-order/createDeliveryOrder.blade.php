@extends('layout.admin')
@section('breadcrumb')
<!--BEGIN BREADCRUMB-->
<ul class="page-breadcrumb">
	<li>
		<a href="index.php">Order</a>
		<i class="fa fa-angle-right"></i>
	</li>
	<li>
		<a href="{{url('frontend/order')}}">Order Management</a>
		<i class="fa fa-angle-right"></i>
	</li>
	<li class="active">Create Delivery Order</li>
</ul>
<!--END BREADCRUMB-->
@stop

@section('content')
<style>
	.scrollDiv{
		overflow-y: auto;
		max-height: 200px;
	}
</style>

<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Incoming Order </h3>
<!-- END PAGE HEADER-->

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-4 col-xs-4" > <h5>Create Delivery Order</h5> </div>
				</div>
			</div>
			<div class="panel-body panel-body-delivery-order">
				<div class="row">
					<div class="col-md-3 col-xs-3"> <strong class="pull-right">Shipping Method</strong> </div>
				</div> <br/>
				<div class="row">
					<div class="col-md-3 col-xs-3"> <p class="pull-right">Pilih Ekspedisi</p> </div>
					<div class="col-md-9 col-xs-9">
						{!! getSelect('position', (@$listShipping['result']) ? :array(), '', $options = array('class' => 'form-control input small','id' => 'ChooseExpedisi','data-resi' => $listShipping['order']->id)) !!}
					</div>
				</div> <br />
				<div class="row">
					<div class="col-md-3 col-xs-3"> <p class="pull-right">Pilih Produk</p> </div>
					<div class="col-md-9 col-xs-9">
						<div class="panel panel-default">
							<div class="panel-body scrollDiv">
								<div class="row">
									<div class="col-md-12 col-xs-12 productLists">
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <br />
				<div class="row">
					<div class="col-md-9 col-xs-9 col-md-offset-3 col-xs-offset-3">
						<input type="hidden" name="order_serial" id="order_serial" value="{{ $listShipping['orderSerial'] }}">
						<input type="hidden" name="orderId" id="orderId" value="{{ $listShipping['order']->id }}">
						<button class="btn green add-price-ship" id="addProductLists" role="button">Add</button>
					</div>
				</div>
				<hr>
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


<script>
  

   
	var total = 0;
	$(document).ready(function() {
       var datepicker = $.fn.datepicker.noConflict();
       $.fn.bootstrapDP = datepicker;

		// $('#nomorSeriForm').formValidation({
		// 	framework: 'bootstrap',
		// 	fields: {
		// 		nomorSeri: {
		// 			validators: {
		// 				notEmpty: {
		// 					message: 'Kolom nomor seri harus diisi'
		// 				},
		// 				stringLength: {
		// 					min: 10,
		// 					max: 12,
		// 					message: 'Kolom nomor seri harus minimal 10 dan maksimal 12'
		// 				},
		// 				regexp: {
		// 					regexp: /^[a-z\s0-9]+$/i,
		// 					message: 'Kolom nomor seri harus huruf dan angka'
		// 				}
		// 			}
		// 		}
		// 	}
		// }).on('success.form.fv', function(e) {
		// 	var nomorResi = $('.get-nomor-seri').val();
		// 	$('.nomor-seri').val(nomorResi);
		// });

		// $('#methodShipForm').formValidation({
		// 	framework: 'bootstrap',
		// 	fields: {
		// 		weightShip: {
		// 			validators: {
		// 				notEmpty: {
		// 					message: 'Kolom weight shipping harus diisi'
		// 				},
		// 				digits: {
		// 					message: 'Kolom weight shipping harus diisi dengan angka'
		// 				}
		// 			}
		// 		},
		// 		priceShip: {
		// 			validators: {
		// 				notEmpty: {
		// 					message: 'Kolom cost shipping harus diisi'
		// 				},
		// 				digits: {
		// 					message: 'Kolom cost shipping harus diisi dengan angka'
		// 				}
		// 			}
		// 		}
		// 	}
		// }).on('success.form.fv', function(e) {
		// 	var Weight = $('.input-weight-ship').val();
		// 	var price = $('.input-price-ship').val();
		// 	total = Weight * price;
		// 	$('.current-total-ship').html('<b>'+displayNumeric(total)+'</b>');
		// });
	});

	// $('.add-price-ship').click(function(){
	// 	$('.total-ship').html(displayNumeric(total));
	// });

	jQuery(document).ready(function() {
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		QuickSidebar.init(); // init quick sidebar
		Demo.init(); // init demo features
		// TableEditable.init();
	});
</script>
@stop