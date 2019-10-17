@extends('layout.admin')
@section('title')
	Order Management |
@stop
@section('breadcrumb')
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
	<li class="active">View</li>
</ul>
<!--END BREADCRUMB-->
@stop

@section('content')
<style>
	.scrollDiv{
		overflow-y: auto;
		max-height: 200px;
	}
	.scrollDiv{
		overflow-y: auto;
		max-height: 200px;
	}
	.modal-title {text-align: center;padding-bottom: 40px;}
	.ico-close-big,
	span.ico-close-big {
		background-image: url(../../public/assets/img/icoClose.png);
		height: 40px !important;
		width: 40px !important;
	}
	.modal-cart.modal-body-home {
		padding: 15px 40px 40px 40px !important;
	}
	.erasepadright{
		padding-right: 0px;
	}
	.fileUpload input.upload {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0;
		padding: 0;
		font-size: 20px;
		cursor: pointer;
		opacity: 0;
		z-index: 90;
		width:103px;
		heigth:34px;
		filter: alpha(opacity=0);
	}
	#uploadFile {
		width: 100%;
		height: 43px;
	}
	.btnUpl{
		background-color: #35384f;
		box-shadow: 0 4px 0 #35384f;
		color: #fff;
		width:103px;
		height:34px;
	}
	.btnUpl:hover, .btnUpl:focus, .btnUpl:active {
		background-color: #35384f;
		box-shadow: 0 4px 0 #35384f;
		color: #fff;    
	}
	.input-group-btn:last-child>.btnUpl{
		margin-left: 0;
	}
	.btn-red {
		background-color: #ed1c24;
		box-shadow: 0px 4px 0px #c1080f;
		color: #fff;
		font-size: 15px;
		width: 100%;
		height: 36px;
		margin-bottom: 4px;
		border-radius: 0;
	}
	.btn-red:hover {
		background-color: #c1080f;
		box-shadow: 0px 4px 0px #c1080f;
		color: #fff;
	} 
	.payment-info .product-status{
		color: #fff;
		background-color: #5BC232;
	} 
	.payment-info .product-status:hover{
		background-color: #7EDD58;
	}
	a.textDecNone{
		text-decoration: none;
	}
	a.text-dec-none{
		text-decoration: none;
	}
	a.text-dec-none:hover{
		cursor: default;
	}
	a.text-dec-none > span{
		background-color: grey; 
	}
	.text-align-center{
		text-align: center;
	}
	#customerOrderStatusDetail .modal-body{
		padding: 30px;
	}
	.panel-green {
		color: #fff;
		background-color: #37b349;
	} 
	.panel-mute {
		color: #999;
		background-color: #ebebeb;
	}
	.padding-top27{
		padding-top: 27px !important;
	} 
</style>
	   
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
	Incoming Order
</h3>
@if(Session::has('success'))
	<div class="alert alert-success">
		<p>{{ Session::get('success') }}</p>
	</div>
@endif
@if(Session::has('failed'))
	<div class="alert alert-danger">
		<p>{{ Session::get('failed') }}</p>
	</div>
@endif
<!-- END PAGE HEADER-->
<div class="row">
	<div id="container-progress" style="text-align:center;width:100%;height:100%;display:none; background-color:rgba(0,0,0,0.7);position:absolute;top:0;left:0;z-index:99999"></div>
		{{--*/ $status_shipping = 0 /*--}}
		<div class="col-md-12">
			<input type="hidden" id="no_order" value="{{@$row->order_serial}}">
			<input type="hidden" id="email_order" value="{{@$row->email}}">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-4 col-xs-4" >
							<h5>View</h5>
						</div>
						<div class="col-md-8 col-xs-8">
							{{--*/ $url = str_replace('/','---',@$row->order_serial) /*--}}
							<a href="{{ url('admin-cp/incoming-order') }}" class="btn btn-default pull-right" role="button" style="">Back</a>
							@if(@$row->payment_status == 'Waiting for Payment')
								<a  href="{{ url('admin-cp/incoming-order/update-order/'.$url) }}" id="btnUpdate" class="btn btn-default pull-right margin-right-10" role="button" style="">Update Order</a>
							@endif
							@if(@$row->payment_status == 'Reject')
							<!--  <a onclick="return confirm('are you sure want to activate this order?')" href="{{ url('admin-cp/incoming-order/activate-order/'.$url) }}" class="btn btn-default pull-right margin-right-10" role="button" style="">Activate Order</a>-->
							<!--  <a onclick="return confirm('are you sure want to reject this order??')" href="{{ url('admin-cp/incoming-order/reject-order/'.$url) }}" class="btn btn-default pull-right margin-right-10" role="button" style="">Reject</a>-->
							@endif
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6 col-xs-6">
							<div class="panel panel-default">
								<div class="panel-heading panel-heading-gray">
									<p>Order {{ $row->order_serial }}</p>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-4 col-xs-4">
											<p>Order Date</p>
										</div>
										<div class="col-md-8 col-xs-8">
											<p>{{ date('M d Y H:i:s', strtotime($row->created_at)) }}</p>
										</div>
									</div>
									<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>Status Order</p>
									</div>
									<div class="col-md-8 col-xs-8">
										<span class="label label-primary status-order">{{ $row->payment_status }}</span>
									</div>
								</div>
								<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>Vendor</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
										@foreach($row->orderDetails as $orderDetails)
											{{--*/ $vendor =  $orderDetails->user->name /*--}} 
										@endforeach
										<p>{{$vendor}}</p>
									</div>
								</div>
								<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>Extended</p>
									</div>
									<div class="col-md-8 col-xs-8">
										<p id="statusExtend">{{ $row->extend_status }}</p>
									</div>
								</div>
								<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p style="margin-top:10px;">Due Date</p>
									</div>
									<div class="col-md-8 col-xs-8">
										<p style="margin-top:10px;" class="due-date" id="dueDate">{{ Carbon\Carbon::parse($row->due_date)->format('d/m/Y') }}</p>
										<input style="float:left !important; width:140px;" type="text" id="extend-due-date" class="btnUpdateOrder form-control margin-right-10 datepicker" placeholder="{{Carbon\Carbon::parse($row->due_date)->format('d/m/Y')}}"/>
										<a class="btn pull-left btn-style-green extend_date">Extend Due Date</a>
									</div>
								</div>
								<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>NPWP</p>
									</div>
									<div class="col-md-8 col-xs-8">
										<p>
											@if(trim($row->user->npwp_filename))
												<a href="{{ url('admin-cp/incoming-order/npwp/'.$row->user->id) }}" class="btn btn-style-blue">Download NPWP</a>
											@else
												N/A
											@endif
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading panel-heading-gray">
								<p>Account Information</p>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>Customer Name</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
										<p>{{ $row->name }}</p>
									</div>
								</div>
								<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>Email</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
										<p>{{ $row->email }}</p>
									</div>
								</div>
								<hr class="order-hr-margin-10">
								<div class="row">
									<div class="col-md-4 col-xs-4">
										<p>Hp</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
										<p>{{ $row->phone_number }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading panel-heading-gray">
								<div class="row">
									<div class="col-md-4 col-xs-4" >
										<h5>Billing Address</h5>
									</div>
									<div class="col-md-8 col-xs-8">
										<!--<a href="#editBilling" id="btnEditBill" class="btn btn-default pull-right" role="button" data-toggle="modal">Edit</a>-->
									</div>
								</div>
							</div>
						@if(isset($billAdd))
						<div class="panel-body">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>Name</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillName">{{ $billAdd->name }}</p>
								</div>
							</div>
							<hr class="order-hr-margin-10">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>Phone</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillPhone">{{ $billAdd->phone }}</p>
								</div>
							</div>
							<hr class="order-hr-margin-10">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>Address</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillAdd">{{ $billAdd->address }}</p>
								</div>
							</div>
							<hr class="order-hr-margin-10">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>Province</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillProv">{{ @$billAdd->province_name }}</p>
								</div>
							</div>
							<hr class="order-hr-margin-10">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>City</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillCity">{{ @$billAdd->city_name }}</p>
								</div>
							</div>
							<hr class="order-hr-margin-10">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>Subdistrict</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillDistrict">{{ @$billAdd->subdistrict_name }}</p>
								</div>
							</div>
							<hr class="order-hr-margin-10">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<p>Postal Code</p>
								</div>
								<div class="col-md-8 col-xs-8 word-wrap">
									<p id="pBillPostal">{{ $billAdd->postal_code }}</p>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				<div class="col-md-6 col-xs-6">
					@if(isset($shipAdd))
						<div class="panel panel-default">
							<div class="panel-heading panel-heading-gray">
								<div class="row">
									<div class="col-md-4 col-xs-4" >
									  	<h5>Shipping Address</h5>
									</div>
									<div class="col-md-8 col-xs-8">
										<!--<a href="#editShipping" id="btnEditShip" class="btn btn-default pull-right" role="button" data-toggle="modal">Edit</a>-->
									</div>
						  		</div>
							</div>
							<div class="panel-body">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>Name</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippName">{{ $shipAdd->name }}</p>
									</div>
						  		</div>
						  		<hr class="order-hr-margin-10">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>Phone</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippPhone">{{ $shipAdd->phone }}</p>
									</div>
						  		</div>
						  		<hr class="order-hr-margin-10">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>Address</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippAdd">{{ $shipAdd->address }}</p>
									</div>
						  		</div>
						  		<hr class="order-hr-margin-10">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>Province</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippProv">{{ @$shipAdd->province_name }}</p>
									</div>
						  		</div>
						  		<hr class="order-hr-margin-10">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>City</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippCity">{{ @$shipAdd->city_name }}</p>
									</div>
						  		</div>
						  		<hr class="order-hr-margin-10">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>Subdistrict</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippDistrict">{{ @$shipAdd->subdistrict_name }}</p>
									</div>
						  		</div>
						  		<hr class="order-hr-margin-10">
						  		<div class="row">
									<div class="col-md-4 col-xs-4">
							  			<p>Postal Code</p>
									</div>
									<div class="col-md-8 col-xs-8 word-wrap">
							  			<p id="pShippPostal">{{ $shipAdd->postal_code }}</p>
									</div>
						  		</div>
							</div>
					  	</div>
					  	@endif
					</div>
				</div>
				<div class="row" id="order-detail">
					@if(Session::has('msg'))
					  	<div class="alert alert-danger">
						  	<p>{{ Session::get('msg') }}</p>
					  	</div>
					@endif
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<input type="hidden" id="totalDetailOrder" value="{{ count($row->orderDetails) }}"/> 
					<form id="orderDetailAdminForm"  role="form" method="post" action="{{ url('/admin-cp/incoming-order/saved-order-detail') }}">
					  	<input type="hidden" name="order_serial" id="order_serial" value="{{ $row->order_serial }}" />
					  	<input type="hidden" name="shipping_subdistrict" value="{{@$shipAdd->subdistrict_id}}" id="shipping_subdistrict"> 
					  	<input type="hidden" name="shipping_city" value="{{@$shipAdd->city_id}}" id="shipping_city">      
					  	<div class="col-md-12">
							<p>Order Detail</p>
								<div class="panel panel-default">
						  			<div class="panel-body panel-body-no-padding">
										<div class="table-responsive">
							  				<table class="table table-bordered  table-margin-bot-0 table-valign-middle">
												<thead>
								  					<tr>
														<td>No</td>
														<td>Product Name</td>
														<td>Status</td>
														<td>Price (Rp)</td>
														<td>Discount (Rp)</td>
														<td>Quantity</td>
														<td>On Event</td>
														<td>Total</td>
														<td>Vendor</td>
														<td>Jasa Pengiriman</td>
														<td>Berat Kirim (kg)</td>
														<td>Biaya Kirim (Rp)</td>
								  					</tr>
												</thead>
												<tbody>
												  	{{--*/ $totalProduct = 0 /*--}}
												  	{{--*/ $totalShipping = 0 /*--}}
												  	{{--*/ $biaya = 0 /*--}}
												  	@foreach($details as $detail)
												  		{{--*/ $flagLunas = 0 /*--}}
												  	<tr class="orderOrder">
													  	<td>{{ $detail['key'] }}</td>
													  	<td>{{ $detail['item']['name'] }}</td>
													  	<td>
															@if($detail['order_log'] == 'Shipping Payment Confirmation')
																<span class="label label-primary">Lunas</span>
																{{--*/ $flagLunas = 1 /*--}}
															@else
																<span class="label label-primary">{{ $detail['order_log'] }}</span>
																<input type="hidden" name="order_detail_id[]" id="order_detail_id" value="{{ $detail['id'] }}" />
																<input type="hidden" name="order_status[]" id="order_status" value="{{ $detail['status_flag'] }}" />
															@endif										 
													  	</td>
													  	<td>
													  		@if($detail['item']['price'])
													  			{{ displayNumericWithoutRp($detail['item']['price']) }}
													  		@else
													  			<span>-</span>
													  		@endif
													  	</td>
													  	<td>
													  		@if($detail['item']['discount'])
													  			{{ displayNumericWithoutRp($detail['item']['discount']) }}
													  		@else
													  			<span>-</span>
													  		@endif
													  	</td>
													  	<td>{{ $detail['item']['quantity'] }}</td>
													  	<td>{!! $detail['event_name'] !!}</td>
													  	<td>{{ displayNumericWithoutRp($detail['total_detail_order']) }}</td>
														{{--*/ $totalProduct = $totalProduct + $detail['total_detail_order']/*--}}
													  	<td>{{ $detail['vendor_name'] }}</td>
														<td>
														  	<div class="order_provider_form">
																{{--*/ $totalProductselectedProvider='' /*--}}
																@foreach($listShipping as $prov)
																  	@if($prov['name'] == $row->shipping->name)
																		{{--*/ $selectedProvider= $prov['name'] /*--}}
																  	@endif
																@endforeach
																@if($flagLunas == '1')
																  	<select disabled="" id="order_provider{{ $detail['key'] }}" name="order_provider[]" class="{{$detail['key']}} form-control order_provider" title="{{$selectedProvider}}">
																		@foreach($listShipping as $keyShip => $value)
																		  	@if($value['name'] == $detail['shipping_name'])
																		  		<option title="{{$value['name']}}" id="{{$detail['key']}}opt{{$keyShip}}" value="{{$value['id']}}" selected>{{$value['name']}}</option>
																		  	@else
																				<option title="{{$value['name']}}" id="{{$detail['key']}}opt{{$keyShip}}" value="{{$value['id']}}">{{$value['name']}}</option>
																		  	@endif
																		@endforeach
																  	</select>
																  	<!--<input type="hidden" name="order_provider[]" value="{{$value['id']}}">-->
																@else  
																  	<select id="order_provider{{$detail['key']}}" name="order_provider[]" class="{{$detail['key']}} form-control order_provider" title="{{$selectedProvider}}">
																		@foreach($listShipping as $keyShip => $value)
																	  		@if($value['name'] == $detail['shipping_name'])
																	  			<option title="{{$value['name']}}" id="{{$detail['key']}}opt{{$keyShip}}" value="{{$value['id']}}" selected>{{$value['name']}}</option>
																	  		@else
																				<option title="{{$value['name']}}" id="{{$detail['key']}}opt{{$keyShip}}" value="{{$value['id']}}">{{$value['name']}}</option>
																	  		@endif
																		@endforeach
																  	</select>
																@endif
														  	</div>
														</td>
													  	<td>
															<div class="order_weight_form">
														  		@if($detail['item']['weight'] == 0.00)
																	{{--*/ $detail['item']['weight'] = '' /*--}}
														  		@endif
														  		@if($flagLunas == '1')
														  			<input disabled="" type="text" class="{{$detail['key']}} form-control order_weight small small_text number-type" id="order_weight" min="1" name ="order_weight[]" value="{{$detail['item']['weight']}}">
														  		@else
														  			<input type="text" class="{{$detail['key']}} form-control order_weight small small_text number-type" id="order_weight" min="1" name ="order_weight[]" value="{{$detail['item']['weight']}}">
														  		@endif
															</div>
													  	</td>
													  	<td class="padding-top27">
															<input type="hidden" id="flagShipping" value="{{$row->free_shipping}}"/> 
															@if($flagLunas == '1')
																<div class="shipping_price_form">
																	<input disabled="" type="text" class="{{$detail['key']}} form-control shipping_price small small_text shipping-fee number-type" id="shipping_price{{$detail['key']}}" name ='shipping_price[]' value="{{$detail['shipping_price']}}">
																	<label class="lblUndefinedPrice{{$detail['key']}} lblUndefinedPrice" style="font-size:12px;color:red"> </label>
																</div>
															@elseif($row->free_shipping == 'Y')
																<div class="shipping_price_form">
																	<input readonly="" type="text" class="{{$detail['key']}} form-control shipping_price small small_text shipping-fee number-type" id="shipping_price{{$detail['key']}}" name ='shipping_price[]' value="0">
																	<label class="lblUndefinedPrice{{$detail['key']}} lblUndefinedPrice" style="font-size:12px;color:red"> </label>
																</div>
															@else
																<div class="shipping_price_form">
																	<input type="text" style="width:100px" class="{{$detail['key']}} form-control shipping_price form-control small small_text shipping-fee number-type" id="shipping_price{{$detail['key']}}" name ='shipping_price[]' value="{{$detail['shipping_price']}}">
																	<label class="lblUndefinedPrice{{$detail['key']}} lblUndefinedPrice" style="font-size:12px;color:red"> </label>
																</div>
															@endif										
													  	</td>
													  	{{--*/ $totalShipping = $totalShipping + $biaya /*--}}
								  					</tr>
								  					@endforeach
												</tbody>
							  				</table>
										</div>
						  			</div>
						  			<div class="panel-footer">
										<div class="row">
							  				<div class="col-xs-3 col-md-3 col-xs-offset-6 col-md-offset-6">
												<p class="pull-right">Total Biaya Produk</p>
							  				</div>
							  				<div class="col-xs-2 col-md-2">
												<p> <span id="productPrice">{{ displayNumeric($row->total_price) }}</span></p>
												<input type="hidden" name="total-biaya-produk" class="total-biaya-produk" value="{{ $row->total_price }}" />
							  				</div>
										</div>
										<div class="row">
							  				<div class="col-xs-3 col-md-3 col-xs-offset-6 col-md-offset-6">
												<p class="pull-right">Total Biaya Shipping</p>
							  				</div>
							  				<div class="col-xs-2 col-md-2">
												<p id="shipping-total" class="shippingPrice">{{ $row->total_shipping_price }} </p>
												<input type="hidden" id="shipping-total-input" value="{{ $row->total_shipping_price }}" />
							  				</div>
										</div>
										<div class="row">
							  				<div class="col-xs-3 col-md-3 col-xs-offset-6 col-md-offset-6">
												<p class="pull-right">Shipping Insurance</p>
							  				</div>
							  				<div class="col-xs-2 col-md-2">
												<p id="insurance" class="insurance">{{ displayNumeric($row->total_shipping_insurance) }} </p>
													<input type="hidden" name="insurance_price" id="insurance_price" value="{{ ceil($row->total_shipping_insurance) }}" />
							  					</div>
											</div>
											<div class="row">
							  					<div class="col-xs-3 col-md-3 col-xs-offset-6 col-md-offset-6">
													<p class="pull-right">Discount</p>
							  					</div>
							  					<div class="col-xs-2 col-md-2">
													<p id="discount" class="discount">{{ displayNumeric($row->discount) }} </p>
													<input type="hidden" name="discount_price" id="discount_price" value="{{ $row->discount }}" />
							  					</div>
											</div>
										<div class="row">
							  				<div class="col-xs-3 col-md-3 col-xs-offset-6 col-md-offset-6">
												<strong class="pull-right">GRAND TOTAL</strong>
							  				</div>
							  				<div class="col-xs-2 col-md-2">
												<p class="grand-total" id="grandTotalOrder"><strong>{{ $row->grand_total}}</strong><p>
												<input type="hidden" name="order_grand_total" id="order_grand_total" value="{{ $row->grand_total }}" />
							  				</div>
										</div>
										<div class="row">
							  				<div class="col-md-4 col-xs-4 col-md-offset-8 col-xs-offset-8">
							  					@if($row->notify_email == 'Y')
													<input type="text" disabled="" class="btn  btn-style-green pull-right" value="Updated"/>
							  					@elseif($row->payment_status == 'Paid')
													<input type="text" disabled="" class="btn  btn-style-green pull-right" value="Update"/>
							  					@else
													<input type="submit" class="btn  btn-style-green pull-right notifyShippingFee" value="Update"/>
							  					@endif
							  				</div>
										</div>
						  			</div>
								</div>
					  		</div>
						</form>
				  	</div>
				  	<div class="row">
						<div class="col-md-12">
							<br>
					  		<p>Payment Information</p>
					  		<div class="table-responsive">
								<table class="table table-bordered table-valign-middle payment-info">
						  			<thead>
										<tr>
							  				<td>No</td>
							  				<td>Product Name</td>
										  	<td>Product Price (Rp)</td>
										  	<td>Shipping Price</td>
										  	<td>Payment Date</td>
										  	<td>Shipping Payment Date</td>  
										  	<td>Bank</td>
										  	<td>Atas Nama</td>
										  	<td>Product Payment Status</td>
										  	<td>Shipping Payment Status</td>
										  	<td>Document</td>
										</tr>
						  			</thead>
						  			<tbody>
									@foreach($row->orderDetails as $keyPay => $orderDetail)
									{{--*/ $flagPaid = 0 /*--}}							
										<tr>
							  				<td>{{ @$keyPay+1 }}</td>
							  				<td>{{ @$orderDetail->item_name }}</td>
							  				<td>{{ displayNumericWithoutRp($orderDetail->price) }}</td>
							  				<td>
								  				@if($row->free_shipping == 'Y')
									  				Gratis
								  				@else
									  				{{ (int) $orderDetail->shipping_price }}
								  				@endif
							  				</td>
							  				<td>
							  					{{ @$row->paid_at }}
								  				@if(@$row->paid_at)
								  					{{--*/ $flagPaid = 1 /*--}}
								  				@else
								  					<span>-</span>
								  				@endif
							  				</td>
							  				<td>{{ @$orderDetail->shippingPayment->paid_at  }}</td>
							  				<td>
							  					<b>From:</b><br> {{ @$row->payments->bank_from }}<br>
							  					<b>To:</b><br> {{ @$row->payments->payment_method }}
							  				</td>
							  				<td>{{ @$row->payments->name }}</td>
							  				<td class="text-align-center">
							  					<span class="label label-success">
													@if( $row->payments)
									  					@if($row->payments->is_verified == 'Y' && $flagPaid == 1)
															Paid
									  					@elseif($row->payments->is_verified == 'N' && $flagPaid == 1)
															Waiting for confirmation
									  					@else 
															Waiting for payment
									  					@endif
													@endif
												</span>
												<br>
												<u>
													<span class="textDecNone" onclick="detailPerOrderDetail('{{encode($orderDetail->id)}}')"> 
								  						<a>View Item History</a>
													</span>
												</u>
							  				</td>
							  				<td class="text-align-center">
												@if($orderDetail->shippingPayment != null)
													<a class="text-dec-none"> 
														<span class="label label-success">{{ $orderDetail->shippingPayment->shipping_payment_status }}</span>
													</a>
													{{--*/ $status_shipping = $status_shipping +1 /*--}}
												@elseif($row->free_shipping == 'Y')
													Gratis
												@endif
							  				</td>
							  				@if($keyPay == 0)
							  				<td rowspan="{{ count($row->orderDetails) }}">
								  				@if(@$row->payments->transfer_img != null)
								  					<a href="{!! cdn('assets/img/bukti-transfer/').$row->payments->transfer_img !!}" class="text-color-green"> Bukti Tranfer Payment </a>
								  					<br><br>
								  				@endif
								  				{{--*/ $flagTrfShipping = 0 /*--}}
								  				@foreach($row->orderDetails as $detail)
													{{--*/ $trf_img = @$detail->shippingPayment->transfer_img /*--}}									
													@if($trf_img != null && $trf_img != @$row->payments->transfer_img && $flagTrfShipping == 0)
									  					<a href="{!! cdn('assets/img/bukti-transfer/').$trf_img !!}" class="text-color-green"> Bukti Tranfer Shipping </a>
									  					{{--*/ $flagTrfShipping = 1 /*--}}
													@endif
								  				@endforeach
							  				</td>
							  			@endif
							  			<td>

							  			</td>
									</tr>
								@endforeach
						  		</tbody>
							</table>
					  	</div>
					</div>
				</div>

					<div class="row">
						<div class="col-md-12">
							<br>
							<p>Payment Information Details</p>
							<div class="table-responsive">
								<table class="table table-bordered table-valign-middle payment-info">
									<thead>
									<tr>
										<td>No</td>
										<td>Created At</td>
										<td>Bank</td>
										<td>Virtual Account </td>
										<td>Trans ID</td>
										<td>Total Price (Rp)</td>
										<td>Amount Transfer (Rp)</td>
										<td>Balance Payment (Rp)</td>
										<td>Payment Date</td>
										<td>Action</td>
									</tr>
									</thead>
									<tbody>
									{{--*/ $per = 1 /*--}}
									{{--*/ $num = count($rowPayments) /*--}}
									{{--*/ $jumTotal = $total = 0 /*--}}
									{{--*/ $jumTransfer = 0 /*--}}
									{{--*/ $jumBalance = 0 /*--}}

									@foreach($rowPayments as $keyPay => $payDetail)
										{{--*/ $flagPaid = 0 /*--}}

										@if($per == 1)
											{{--*/ $total = $jumTotal = $payDetail->total /*--}}
											{{--*/ $jumTransfer = $payDetail->nominal_transfer /*--}}
											{{--*/ $cstyle = '' /*--}}
											{{--*/ $add = '' /*--}}
											{{--*/ $min = '' /*--}}
										@elseif($payDetail->bayar == 1 && $per < $num)
											{{--*/ $jumTotal -= 0 /*--}}
											{{--*/ $jumTransfer += 0 /*--}}
											{{--*/ $cstyle = 'text-decoration: line-through' /*--}}
											{{--*/ $add = '' /*--}}
											{{--*/ $min = '' /*--}}
										@else
											{{--*/ $jumTotal -= $payDetail->total /*--}}
											{{--*/ $jumTransfer += $payDetail->nominal_transfer /*--}}
											{{--*/ $cstyle = '' /*--}}
											{{--*/ $add = '(+)' /*--}}
											{{--*/ $min = '(-)' /*--}}
										@endif

										{{--*/ $jumBalance = $payDetail->balance_payment /*--}}

										<tr>
											<td>{{ $per++ }}</td>
											<td>{{ $payDetail->created_at }}</td>
											<td>{{ @$payDetail->bank_from }}</td>
											<td>{{ @$payDetail->va_payment }}</td>
											<td>{{ @$payDetail->tid_payment }}</td>
											<td style="{{$cstyle}}">Rp. {{ displayNumericWithoutRp($payDetail->total) }} <sub>{{$min}}</sub></td>
											<td style="{{$cstyle}}">Rp. {{ displayNumericWithoutRp($payDetail->nominal_transfer) }}} <sub>{{$add}}</sub></td>
											<td style="{{$cstyle}}">Rp. {{ displayNumericWithoutRp($payDetail->balance_payment) }}}</td>
											<td>{{ $payDetail->bayar }}</td>
											<td class="text-align-center">
													@if($payDetail->is_verified == 'Y')
													    <span class="label label-success">
																Paid
														</span>
													@elseif($row->is_verified == 'N' && $payDetail->bayar == 1 && $per <= $num)
														Expired
													@elseif($row->is_verified == 'N' && $payDetail->bayar == 1 && $per >= $num)
														<span class="textDecNone" onclick="reOrderVa('{{encode($payDetail->id)}}')">
															Expired
														</span>
													@else
														<span class="label label-success">
															Waiting for payment
														</span>
													@endif
											</td>
										</tr>

									@endforeach

									{{--*/ $descr = $nom = '' /*--}}

									@if($jumBalance < 0)
											{{--*/ $descr = "Under Paid "; /*--}}
                                        @elseif($jumBalance > 0)
                                        	{{--*/ $descr = "Over Paid "; /*--}}
                                        @else
                                            {{--*/ $descr = "Matched "; /*--}}
                                        @endif

                                        <tr>
                                            <td colspan="5">Total Payment ({ {displayNumericWithoutRp($jumTransfer) }} - {{ displayNumericWithoutRp($totsl) }})</td>
                                            <td>Rp. {{ displayNumericWithoutRp($jumTotal) }}</td>
                                            <td>Rp. {{ displayNumericWithoutRp($jumTransfer) }}</td>
                                            <td><strong> Rp. {{ displayNumericWithoutRp($jumBalance) }} </strong> </td>
                                            <td colspan="2"><strong>{{$descr}}</strong> (Rp. {{displayNumericWithoutRp($jumBalance)}} )</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <p> Shipping Information</p>
                        </div>
                        <div class="col-lg-6"></div>
                    </div>
                      <div class="row">
                        <div class="col-md-12">
                              <div class="table-responsive">
                                <table class="table table-bordered table-valign-middle">
                                      <thead>
                                        <tr>
                                              <td>No</td>
                                              <td>Vendor</td>
                                              <td>Product Name</td>
                                              <td>Jasa Pengiriman</td>
                                              <td>Tanggal Pengiriman</td>
                                              <td>Nomor Resi</td>
                                              <td>Delivery Order Approved</td>
                                              <td>Action</td>
                                        </tr>
                                      </thead>
                                      <tbody>
                                    {{--*/ $totalProduct = 0 /*--}}
								{{--*/ $totalShipping = 0 /*--}}
								{{--*/ $biaya = 0 /*--}}
								{{--*/ $flagDlDo = 0 /*--}}
								@foreach($row->orderDetails as $keyship => $orderDetail)
									<tr>
							  			<td>{{ @$keyship+1 }}</td>
							  			<td>{{ @$orderDetail->user->name }}</td>
							  			<td>{{ @$orderDetail->item_name }}</td>
							  			<td>{{ @$row->shipping->name }}</td>
									  	<td>
											@if(@$orderDetail->statusOrderDelivered)
										  		{{ @$orderDetail->statusOrderDelivered->created_at }}
											@endif
									  	</td>
									  	<td>
									  		{{ @$orderDetail->no_resi }}<input type="hidden" id="resi{{$keyship}}" value="{{ $orderDetail->no_resi }}"></td>
									  	<td>
									  		{{--*/ $status_approve = @$orderDetail->deliveryOrderDetail->deliveryOrder->do_status /*--}} 
									  		@if($status_approve != 'Waiting for Approval')
									 		 	{{ $approve_date = @$orderDetail->deliveryOrderDetail->deliveryOrder->approve_date }} <br>							  
												<b> By: {{ @$orderDetail->deliveryOrderDetail->deliveryOrder->approve_name }}</b>
									  		@endif
									  	</td>
									  	@if($flagDlDo == 0)
											<td rowspan="{{ count($row->orderDetails) }}">
												@foreach($row->deliveryOrder as $do)
										  			{{--*/ $doSerial = str_replace('/','---', @$do->delivery_order_serial) /*--}}
										  			<a href="{{ url('admin-cp/incoming-order/create-delivery-order/'.$doSerial) }}" id="btnShippingView" class="btn btn-default btn-style-label-small  pull-left" role="button" style="">Download {{ @$do->delivery_order_serial }}</a>
										  			<br>
										  			<br>
												@endforeach  
												{{--*/ $flagDlDo = 1 /*--}}
											</td> 
									  	@endif
									</tr>
								@endforeach
						  	</tbody>
						</table>
					</div>
				</div>
				</div>
				  	<div class="row">
						<div class="col-md-12 col-xs-12">
							{!! Form::model(@$row, ['files'=>true, 'class'=>'validated', 'id' => 'form-comment-history']) !!}
					  			<div class="panel panel-default">
									<div class="panel-heading panel-heading-gray">
						  				<p>Comment History</p>
									</div>
									<div class="panel-body">
						  				<div class="row">
											<div class="col-md-2 col-xs-2">
							  					<p>Add Order Comments</p>
											</div>
											<div class="col-md-10 col-xs-10">
							  					{!! Form::radio('orderCmnd', 'top') !!} Term of Payment &nbsp;&nbsp;&nbsp;
							  					{!! Form::radio('orderCmnd', 'onepy', true) !!} One Payment <br><br>
											</div>
						  				</div>
						  				<hr class="order-hr-margin-10">
						  				<div class="row">
											<div class="col-md-2 col-xs-2">
							  					<p>Status</p>
											</div>
											<div class="col-md-10 col-xs-10">
							  					{!! getSelect('order_log', $statusLog, '', $options = array('class' => 'required statusHistory', 'id' => $row->order_serial)) !!} <br><br>
											</div>
						  				</div>
						  				<hr class="order-hr-margin-10">
						  				<div class="row">
											<div class="col-md-2 col-xs-2">
							  					<p>Item List</p><label id="itemDetail[]-error" class="error" for="itemDetail[]" style="display:none;">This field is required.</label>
											</div>
											<div class="col-md-10 col-xs-10">
							  					<div class="scrollDiv">
													<div id="itemOrder">
														@foreach($row->orderDetails()->get() as $keyItem => $orderDetail)
								  							@if( @$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log != 'Reject' &&
																@$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log != 'Order Received')
																<span>
																	{!! Form::checkbox('itemDetail[]', $orderDetail->id, null, ['class' => 'required checkboxItem' , 'id' => 'item'.$keyItem ]) !!} 
																	<span class="prodWrap">{!! $orderDetail->item_name !!}</span>
																	@if(@$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log == 'Shipping Payment Confirmation')
																		<span class="label label-success">Lunas </span>
																	@else
																		<span class="label label-primary"> {!! @$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log !!} </span> 
																	@endif
																</span>
																<br>
																<br>
								  							@endif
														@endforeach 
													</div>								
							  					</div>
											</div>
						  				</div>
						  				<div id="input_resi"></div>
						  				<hr class="order-hr-margin-10">
						  				<div class="row">
											<div class="col-md-2 col-xs-2">
							  					<p>Comment</p>
											</div>
											<div class="col-md-10 col-xs-10">
							  					{!! Form::textarea('notes', null, ['size' => '50x4','id' => 'notes']) !!}
											</div>
						  				</div>
						  				<hr class="order-hr-margin-10">
						  				<div class="row">
											<div class="col-md-10 col-xs-10 col-md-offset-2 col-xs-offset-2">
							  					{!! Form::checkbox('notify', 'Y', ['class' => 'focus']) !!} Notify Customer by Email <br>
							  					{!! Form::checkbox('visible', 'Y' , ['class' => 'focus']) !!} Visible on Frontend
							  					{!! Form::text('orderSerial', @$row->order_serial, array('hidden' => 'hidden')) !!}							  
											</div>
						  				</div>
									</div>
									<div class="panel-footer">
						  				<div class="row">
											<div class="col-md-10 col-xs-10 col-md-offset-2 col-xs-offset-2">
							  					<div id="btnSubmitCommentHistory"> 
							  						<button class="btn btn-default submit-comment-history" role="button">Submit</button>
							  					</div>
											</div>
						  				</div>
									</div>
					  			</div>
					  			{!! Form::close() !!}
							</div>
				  		</div>
					</div>
			  	</div>
			</div>
		</div>
		<div id="editShipping" class="modal fade" tabindex="-1" role="basic">
			<div class="modal-dialog modal-lg">
			  	<form id="ship-address" method="post" action="{{ url('/admin-cp/incoming-order/edit-shipping/'.$url) }}" >
					<div class="modal-content">
				  		<div class="modal-header modal-header-no-border">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title text-center">Edit Shipping Address</h4>
				  		</div>
				  		<div class="modal-body">
							<div class="row">
					  			<div class="col-md-12">
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Name</p>
						  				</div>
							  			<div class="col-md-9 col-xs-9">
											<input type="text" id="shipName" class="form-control" value="{{ @$shipAdd->name }}" name="shipName" />
							  			</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Phone</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<input type="text" id="shipPhone" class="form-control" name="shipPhone" value="{{ @$shipAdd->phone }}" />
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Address</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<textarea class="form-control" id="shipAdd" rows="3" name="shipAdd">{{ @$shipAdd->address }}</textarea>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Province</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<select class="form-control" id="shipProv" name="shipProv" data-placeholder="Select...">
							 					<option value = ''>Province*</option>								
							  					@foreach($provinces as $keyShipProv => $shipprovince)
								  					@if($keyShipProv == @$shipAdd->province_id)
									  					<option value = '{{$keyShipProv}}' selected>{{$shipprovince}}</option>
								  					@else
									  					<option value = '{{$keyShipProv}}'>{{$shipprovince}}</option>
								  					@endif
							  					@endforeach							  
											</select>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">City</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<select class="form-control " id="shipCity" name="shipCity" data-placeholder="Select...">
							  					<option value = ''>City*</option>
												@foreach($citiesShip as $keyShipCity => $shipcity)
													@if($keyShipCity == @$shipAdd->city_id)
														<option value = '{{$keyShipCity}}' selected>{{$shipcity}}</option>
													@else
														<option value = '{{$keyShipCity}}'>{{$shipcity}}</option>
													@endif
												@endforeach
											</select>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Subdistrict</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<select class="form-control" id="shipDistrict" name="shipDistrict" data-placeholder="Select...">
							  					<option value = ''>Subdistrict*</option>								
							  					@foreach($subdistrictShip as $keyShipSub => $shipsub)
								  					@if($keyShipSub == @$shipAdd->subdistrict_id)
									  					<option value = '{{$keyShipSub}}' selected>{{$shipsub}}</option>
								  					@else
									  					<option value = '{{$keyShipSub}}'>{{$shipsub}}</option>
								  					@endif
							  					@endforeach
											</select>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Postal Code</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<input type="text" class="form-control" id="shipPostalCode" name="shipPostalCode" value="{{ @$shipAdd->postal_code }}" />
						  				</div>
									</div>
					  			</div>
							</div>
				  		</div>
				  		<div class="modal-footer">
							<div class="row">
					  			<div class="col-md-3 col-xs-3"></div>
					  			<div class="col-md-9 col-xs-9">
									<input type="submit" class="btn green pull-left" id="btnShipSave" value="Save">
									<button class="btn btn-default pull-left" id="btnShipCancel" role="button" data-dismiss="modal">Cancel</button>
					  			</div>
							</div>
				  		</div>
					</div>
			  	</form>
			</div>
		  </div>
		  <div id="editBilling" class="modal fade" tabindex="-1" role="basic">
			<div class="modal-dialog modal-lg">
			  	<form id="bill-address" method="post" action="{{ url('/admin-cp/incoming-order/edit-billing/'.$url) }}" >
					<div class="modal-content">
				  		<div class="modal-header modal-header-no-border">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title text-center">Edit Billing Address</h4>
				  		</div>
				  		<div class="modal-body">
							<div class="row">
					  			<div class="col-md-12">
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Name</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<input type="text" class="form-control" name="billName" id="billName" value="{{ @$billAdd->name }}" />
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Phone</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<input type="text" class="form-control" name="billPhone"  id="billPhone" value="{{ @$billAdd->phone }}" />
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Address</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<textarea class="form-control" rows="3"  name="billAddress"  id="billAddress" name="billAddress" >{{ @$billAdd->address }}</textarea>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Province</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<select class="form-control" name ="billProv" id ="billProv">
												<option value = ''>Province*</option>								
								  				@foreach($provinces as $keyBillProv => $billprovince)
									  				@if($keyBillProv == @$billAdd->province_id)
										  				<option value = '{{$keyBillProv}}' selected>{{$billprovince}}</option>
									  				@else
										  				<option value = '{{$keyBillProv}}'>{{$billprovince}}</option>
									  				@endif
								  				@endforeach									
											</select>							
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">City</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<select class="form-control " id="billCity" name="billCity"  data-placeholder="Select...">
							   					<option value = ''>City*</option>								
												@foreach($citiesBill as $keyBillCity => $billcity)
													@if($keyBillCity == @$billAdd->city_id)
														<option value = '{{$keyBillCity}}' selected>{{$billcity}}</option>
													@else
														<option value = '{{$keyBillCity}}'>{{$billcity}}</option>
													@endif
												@endforeach
											</select>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Subdistrict</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<select class="form-control " id="billDistrict"  name="billDistrict" data-placeholder="Select...">
								  				<option value = ''>Subdistrict*</option>								
								  				@foreach($subdistrictBill as $keyBillSub => $billsub)
									  				@if($keyBillSub == @$billAdd->subdistrict_id)
										  				<option value = '{{$keyBillSub}}' selected>{{$billsub}}</option>
									  				@else
										  				<option value = '{{$keyBillSub}}'>{{$billsub}}</option>
									  				@endif
								  				@endforeach
											</select>
						  				</div>
									</div>
									<br />
									<div class="row">
						  				<div class="col-md-3 col-xs-3">
											<p class="edit-shipping-p">Postal Code</p>
						  				</div>
						  				<div class="col-md-9 col-xs-9">
											<input type="text" class="form-control" id="billPostal" name="billPostal"  value="{{ @$billAdd->postal_code }}" />
						  				</div>
									</div>
					  			</div>
							</div>
				  		</div>
				  		<div class="modal-footer">
							<div class="row">
					  			<div class="col-md-3 col-xs-3"></div>
					  			<div class="col-md-9 col-xs-9">
									<button class="btn green pull-left" id="btnSubmitBill" role="button" data-dismiss="modal">Submit</button>
									<button class="btn btn-default pull-left" role="button" data-dismiss="modal">Cancel</button>
					  			</div>
							</div>
				  		</div>
					</div>
			  	</form>
			</div>
		</div>
	  	<div class="modal fade" id="konfirmPembayaranPayment" tabindex="-1">
			<div class="modal-dialog modal-konfirm">
	  			<div class="modal-content eraseRad">
					<div class="modal-header">
		  				<button type="button" class="close btnClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
					  			<span class="modal-close ico ico-close-big" data-dismiss="modal"></span>
							</span>
		  				</button>
					</div>
					<div class="modal-body modal-body-home modal-cart">
		  				<div class="modal-body modal-body-home modal-cart">
	  						<div class="loading-confirm-payment" style="display:none; background-color: transparent;opacity: 0.7;width: 433px !important;height: 603px;position: absolute;z-index: 10;">
								<center><img class="img-loading-lg" src="{!! cdn('assets/img/loading-image.svg') !!}"></center>
	  						</div>
		  					<h4 class="modal-title" id="konfirmPembayaranPayment" style="font-size:20px;"><b>Konfirmasi Pembayaran</b></h4>
		  					{!! Form::open(['id'=>'form-konfirmasi-payment', 'method'=>'post', 'files'=>true ]) !!}
								<div class="form-group">
				  					<input type="text" class="form-control marBottom10 formInput" name="order_serial" id="order_serial_payment" placeholder="Order ID.." readonly>
			  						<div class="error-message error-message-k"></div>
			  						<p style="color:#ED1C25" class="error-msg" id="error-order_serial_payment"></p>
								</div>
								<div class="form-group">
					  				<input type="text" class="form-control marBottom10 formInput" name="name" id="name" placeholder="Nama Lengkap..">
					  				<div class="error-message error-message-k"></div>
					  				<p style="color:#ED1C25" class="error-msg" id="error-name_payment"></p>
								</div>
								<!-- <div class="form-group">
					  				<select class="form-control marBottom10 formInput" id="bank_tujuan" name="bank_tujuan" style="color:#999 !important;">
										<option value="">Bank Tujuan..</option>
										<option calue="BCA">BCA</option>
					  				</select>
						  			<p style="color:#ED1C25" class="error-msg" id="error-bank_tujuan"></p>
								</div> -->
								<div class="form-group">
					  				<input type="text" class="form-control marBottom10 formInput" name="bank_asal" id="bank_asal" placeholder="Bank Anda..">
					  				<div class="error-message error-message-k"></div>
					  				<p style="color:#ED1C25" class="error-msg" id="error-bank_asal_payment"></p>
								</div>
								<div class="form-group">
					  				<div class="col-sm-4 col-xs-12 erasepadLeft">
										<input type="text" id="tanggal" class="form-control marBottom10 formInput datepicker" name="tanggal" placeholder="Tanggal" />
										<p style="color:#ED1C25" class="error-msg" id="error-tanggal_payment"></p>
					  				</div>
						  			<div class=" col-sm-3 col-xs-6 erasepadLeft">
										<div class="bootstrap-timepicker">
							  				<input id="jam" type="text" placeholder="00:00" class="form-control marBottom10 formInput input-small" name="jam">
							  				<p style="color:#ED1C25" class="error-msg" id="error-jam_payment"></p>
										</div>
						  			</div>
						  			<div class="col-sm-5 col-xs-6 noPadding">
										<input type="text" class="form-control marBottom10 formInput" id="atas_nama" name="atas_nama" placeholder="Atas Nama..">
										<div class="error-message error-message-k"></div>
										<p style="color:#ED1C25" class="error-msg" id="error-atas_nama_payment"></p>
						  			</div>
								</div>
								<div class="col-xs-12 no-padding form-group">
					  				<input type="text" class="form-control marBottom10 formInput" name="amount" id="amount" placeholder="Nominal Transfer..">
					  				<div class="error-message error-message-k"></div>
					  				<p style="color:#ED1C25" class="error-msg" id="error-amount_payment"></p>
								</div>
								<div class="form-group input-group">
					  				<input id="uploadFile_payment" class="form-control" placeholder="Upload foto bukti transfer.." readonly="">
					  				<span class="fileUpload input-group-btn">
										<input id="bukti_transfer_payment" type="file" class="upload uploadBtn" name="bukti_transfer" />
										<a class="btn btnUpl browse-file">Browse File</a>
					  				</span>
								</div>
				  				<div class = 'form-group'>
									<p style="color:#ED1C25" class="error-msg" id="error-bukti_transfer_payment"></p>
				  				</div>
								<div class="form-group">
					  				<button type="submit" class="btn eraseRad btn-red" id="konfirm-p-payment" style="position:static !important;">Konfirmasi</button>
								</div>
		  						{!! Form::token() !!}
		  					{!! Form::close() !!}
						</div>
	  				</div>
				</div>
  			</div>
		</div>

		<div class="modal fade" id="modal-message" tabindex="-1">
			<div class="modal-dialog">
	  			<div class="modal-content eraseRad">
					<div class="modal-header">
		  				<button type="button" class="close btnClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
			  					<span class="modal-close ico ico-close-big" data-dismiss="modal"></span>
							</span>
		  				</button>
					</div>
					<div class="modal-body modal-body-home modal-konfirmemail">
			  			<h3 class="modal-title" id="message-title" style="font-size:18px;"><b>Success</b></h3>
			  			<h4>
							<p class="text-center" id="return-message"></p>
			  			</h4>
			  			<button type="button" class="btn eraseRad btn-red" data-dismiss="modal" style="position:static !important;">
							<strong>OKE</strong>
			  			</button>
					</div>
	  			</div>
			</div>
  		</div>
		<div class="modal fade" id="cekStatus" tabindex="-1">
  			<div class="modal-dialog modal-cek-status">
				<div class="modal-content eraseRad">
	  				<div class="modal-header">
						<button type="button" class="close btnClose" data-dismiss="modal" aria-label="Close">
			  				<span aria-hidden="true">
								<span class="modal-close ico ico-close-big" data-dismiss="modal"></span>
			  				</span>
						</button>
	  				</div>
	  				<div class="modal-body modal-body-home" id="status-order-content" style="padding:15px !Important">
						<!-- data order -->
	  				</div>
				</div>
  			</div>
		</div>
		<!-- SUB: Order Status -->
  		<div class="modal fade" id="customerOrderStatusDetail" tabindex="-1">
			<div class="modal-dialog modal-xl">
	  			<div class="modal-content">
					<div class="modal-body" id = 'order_status_log_detail'>
						<!-- detail order status -->
					</div>
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
<link href="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') !!}" rel="stylesheet" type="text/css"/>
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
<script src="{!! asset('assets/js/frontend/form-validation/formValidation.min.js') !!}"></script>
<script src="{!! asset('assets/js/frontend/form-validation/bootstrap.min.js') !!}"></script>
<!--<script src="{!! asset('assets/admin/metronic/global/plugins/ckeditor/ckeditor.js') !!}"></script>-->
<script type="text/javascript" src="{!! asset('assets/admin/js/script.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/admin/js/jquery-ui-timepicker-addon.js') !!}"></script>
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
        return "{{ cdn('admin-cp') }}/" + suffix;
    };
    var asset = function(suffix) {
        return "{{ cdn('') }}/" + suffix;
    };
</script>
<script type="text/javascript" src="{!! asset('assets/admin/js/jquery.validate.js') !!}"></script>
<script src="{!! asset('assets/js/frontend/incoming-order-cms-script.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/dropzone/dropzone.js') !!}"></script>

<script>
    $('.order_provider').change(function(){
        $(this).find("option:selected").each(function() {
            str = $(this).text();

        });
        $(this).attr('title',str);
    });

    $("select[name='order_log']").change(function(){
        $("#container-progress").html("<i class='fa fa-cog fa-spin' style='margin:25% auto;font-size:60px;color:#ffffff'></i>");
            $('body').addClass('modal-open');
            window.scrollTo(0,0);
            $("#container-progress").css("display", "block");
        $.ajax({
            type: "GET",
            url: admin_url("incoming-order/select-status"),
            data: {
                status: this.value,
                serial: $(this).attr('id')
            }
        }).done(function(result) {
            var select_id = $('.statusHistory').val();

            if(select_id == '1'){
                var paid_at = '<?php echo @$row->paid_at ?>';
                if(paid_at == null || paid_at == ''){
                    $('#btnSubmitCommentHistory').html('<a class="btn btn-default submit-comment-history" onclick=modalKonfirmPembayaran("{{$row->order_serial}}") data-toggle="modal"> Submit</a>');
                }
            }else if(select_id == '8'){
                /*var status_shipping = '<?php echo $status_shipping ?>';
                var total_detail_order = '<?php echo count($row->orderDetails) ?>';
                //cek shipping order status
                if(status_shipping != total_detail_order){
                    $('#btnSubmitCommentHistory').html('<a class="btn btn-default submit-comment-history" onclick=modalKonfirmPembayaran("{{$row->order_serial}}") data-toggle="modal"> Submit</a>');
                }*/

                var paid_at = '<?php echo @$row->paid_at ?>';
                if(paid_at == null || paid_at == ''){
                    $('#btnSubmitCommentHistory').html('<a class="btn btn-default submit-comment-history" onclick=modalKonfirmPembayaran("{{$row->order_serial}}") data-toggle="modal"> Submit</a>');
                }
            }else{
                $('#btnSubmitCommentHistory').html('<button class="btn btn-default submit-comment-history" role="button">Submit</button>');
        }

        if(select_id == '3'){
            var field_resi = '<hr class="order-hr-margin-10">'+
                    '<div class="row">'+
                          '<div class="col-md-2 col-xs-2">'+
                                '<p>No Resi</p>'+
                          '</div>'+
                          '<div class="col-md-10 col-xs-10">'+
                                '{!! Form::text("no_resi", null,["class"=>"form-control", "id"=>"no_resi",   "required"=>"required"]) !!}'+
                          '</div>'+
                        '</div>';
            $('#input_resi').html(field_resi);
        }else{
            $('#input_resi').html('');
        }

        $('.submit-comment-history').prop('disabled', false);
        $('.submit-comment-history').css('color', 'white');
        $('body').removeClass('modal-open');
        $("#container-progress").hide();
        $("#notes").val(result.value);
        $("#itemOrder").html(result.item);

        //validation delivery order
        $('.checkboxItem').click(function(){
             var selected_status = $('.statusHistory').val();
             var status_item = $(this).attr('id');
             var id = status_item.substr(4);
             var id_resi = 'resi'+id;
             var status_resi = $('#'+id_resi).val();

          });
      })
    });

    $(".shipping_price").change(function(){

        var price = $(this).val();
        var productPrice = $("#productPrice").val();
        var totalDetailOrder = $("#totalDetailOrder").val();
        var totalItem = $('.shipping_price').length;

        if(price != 'biaya belum ditentukan'){
            $(this).closest('.orderOrder').find('.lblUndefinedPrice').html('');
            // $('.lblUndefinedPrice').html('');
        }

    });

    $(".extend_date").click(function(){
        if($('#extend-due-date').val() == '') {
            $('.extend-warning').show();
            $('.extend-warning').text('Extend date harus diisi');
        }else{
            $('.extend-warning').hide();
            $('.extend-warning').text('');
            var order_serial = "<?php echo $row->order_serial  ?>";
            var extend_date = $("#extend-due-date").val();
            var split = (extend_date).split('-');
            var changeextend_date = split[1]+"/"+split[0]+"/"+split[2];
            $("#container-progress").html("<i class='fa fa-cog fa-spin' style='margin:25% auto;font-size:60px;color:#ffffff'></i>");
            $('body').addClass('modal-open');
            window.scrollTo(0,0);
            $("#container-progress").css("display", "block");

            $.post("{{ action('Admin\Order\IncomingOrderController@postExtendDueDate') }}",{
              'order_serial':order_serial,
              'extend_date': changeextend_date
            }, function(data){
                $('body').removeClass('modal-open');
                $("#container-progress").hide();
                if(data['status'] == 'Success'){
                    $('#statusExtend').html('Yes');
                    $('#dueDate').html(data.result);
                    alert('extend success');
                }else{
                    alert('due date has been extended');
                }
            });
        }
    });


    $('#orderDetailAdminForm').formValidation({
        framework: 'bootstrap',
        trigger: 'blur',
        fields: {
            'order_weight[]': {
                // The children's full name are inputs with class .childFullName
                selector: '.order_weight',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.order_weight_form',
                validators: {
                    notEmpty:{
                        message: '<span style="color:red;  font-size:12px">berat harus diisi</span>'
                    }
                }
            },
            shipping_price: {
                // The children's full name are inputs with class .childFullName
                selector: '.shipping_price',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.shipping_price_form',
                validators: {
                    notEmpty:{
                        message: '<span style="color:red;  font-size:12px"> harga harus diisi</span>'
                    }
                }
            },
            'order_provider[]': {
                // The children's full name are inputs with class .childFullName
                selector: '.order_provider',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.order_provider_form',
                validators: {
                    notEmpty:{
                        message: '<span style="color:red; font-size:12px">Pilih ekspedisi lain</span>'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
         //alert('success');
    });


    var so = $('.status-order').text();
    if(so === 'Waiting for Payment'){
        $('#btnUpdate').css('display','block');
    }else if(so === 'Reject' || so === 'Delivered' || so === 'Paid'){
        $('#btnUpdate').remove();
        if(so === 'Paid'){
            $('#extend-due-date').remove();
            $('.extend_date').remove();
        }
    }

    /*$(".order_weight").change(function(){
        var weight = $(this).val();
        //var provider = $('#order_provider').val();
        var provider = $(this).closest('.orderOrder').find('.order_provider option:selected').val();
        var district = $('#shipping_subdistrict').val();
        var city = $('#shipping_city').val();
        var rowid = $(this).attr('class');
        //var skuClass = skuClass.substr(0,skuClass.indexOf(' '));
        var rowid = rowid.substr(0,rowid.indexOf(' '));
        var shiprow = "shipping_price"+rowid;
        var lblUndefinedPrice = 'lblUndefinedPrice'+rowid;
        //alert(rowid);
        $("#container-progress").html("<i class='fa fa-cog fa-spin' style='margin:25% auto;font-size:60px;color:#ffffff'></i>");
        $('body').addClass('modal-open');
        window.scrollTo(0,0);
        $("#container-progress").css("display", "block");

        if(provider != '' && weight!=0 && $('#flagShipping').val() != 'Y'){
            $.post("{{ action('Admin\Order\IncomingOrderController@postShippingPrice') }}",{
                'order_provider':provider,
                'order_weight': weight,
                'shipping_city': city,
                'shipping_subdistrict': district
            }, function(data){
                $('body').removeClass('modal-open');
                $("#container-progress").hide();
                //$(this).closest('.orderOrder').find('.shipping_price').val("1111");
                if(data != 'false' ){
                    if(data.price == 'biaya belum ditentukan'){
                        $('.notifyShippingFee').addClass('disabled');
                        $('.notifyShippingFee').prop('disabled', true);
                        $('.'+lblUndefinedPrice).html('pilih ekspedisi lain');
                        // $('#order_provider').val('');
                        // $(".order_provider option:selected").text();
                        // var id = $('.order_provider').find("option:selected").attr("id");
                        // alert(id);
                        // $(this).attr('value','');
                        order_prov_id = 'order_provider'+rowid;
                        //alert(order_prov_id);
                        var id = $('#'+order_prov_id).find("option:selected").attr("id");
                        $('#'+id).val('');
                        alert('Ubah jasa pengiriman ke ekspedisi lain');
                    }
                    if(data.price == 'null'){
                        data.price = 0;
                    }
                    $('#'+shiprow).val(data.price);

                    var total = 0;
                    var grandtotal = 0;
                    $('#order-detail').find('.shipping-fee').each(function() {
                        total += parseInt($(this).val());
                    });
                    // GUNAKAN MASKING AGAR ADA SEPARATOR PADA TOTAL
                    var totalproduksi = parseInt($('.total-biaya-produk').val());
                    var getdiscount = $('.discount').text();
                    var replaceRp = getdiscount.replace('Rp','');
                    var replaceSpasi = replaceRp.replace(' ','');
                    var replaceDot = replaceSpasi.replace(/\./g,'');
                    var discount =  parseInt(replaceDot);
                    var insurance = parseInt($('#insurance_price').val());
                    var kode_unik = parseInt($('#unikCode').text());

                    $("#shipping-total").text(displayNumeric(total));
                    grandtotal += totalproduksi;
                    grandtotal += total;
                    grandtotal += insurance;
                    grandtotal -= discount;
                    grandtotal += kode_unik;$(".grand-total").text(displayNumeric(grandtotal));
                    console.log(data);
                }else{
                    $('.'+lblUndefinedPrice).html('');
                }
            });
        }else{
           $('body').removeClass('modal-open');
                $("#container-progress").hide();
        }
    });*/

    $("#bill-address").formValidation({
        framework: 'bootstrap',
        fields: {
            billName: {
                validators: {
                    notEmpty: {
                        message: 'Kolom nama harus diisi'
                    }
                }
            },
            billPhone: {
                validators: {
                    notEmpty: {
                        message: 'Kolom handphone harus diisi'
                    },
                    digits: {
                        message: 'Kolom handphone harus diisi dengan angka'
                    },
                    stringLength: {
                        min: 10,
                        max: 12,
                        message: 'Kolom handphone harus diisi dengan angka minimal 10 karakter dan maksimal 12 karakter contohnya 0811223345, 081122334534'
                    }
                }
            },
            billAddress: {
                validators: {
                    notEmpty: {
                        message: 'Kolom alamat harus diisi'
                    }
                }
            },
            billProv: {
                validators: {
                    notEmpty: {
                        message: 'Kolom provinsi harus diisi'
                    }
                }
            },
            billCity: {
                validators: {
                    notEmpty: {
                        message: 'Kolom kota harus diisi'
                    }
                }
            },
            billDistrict: {
                validators: {
                    notEmpty: {
                        message: 'Kolom kecamatan harus diisi'
                    }
                }
            },
            billPostal: {
                validators: {
                    notEmpty: {
                        message: 'Kolom kode pos harus diisi'
                    },
                    stringLength: {
                        min: 5,
                        max: 5,
                        message: 'Kolom kode pos harus terdiri dari lima digit'
                    },
                    digits: {
                        message: 'Kolom kode pos harus terdiri dari 0-9'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
        $('#editBilling').modal('toggle');
    });

    $("#ship-address").formValidation({
        framework: 'bootstrap',
        fields: {
            shipName: {
                validators: {
                    notEmpty: {
                        message: 'Kolom nama harus diisi'
                    }
                }
            },
            shipPhone: {
                validators: {
                    notEmpty: {
                        message: 'Kolom handphone harus diisi'
                    },
                    digits: {
                        message: 'Kolom handphone harus diisi dengan angka'
                    },
                    stringLength: {
                        min: 10,
                        max: 12,
                        message: 'Kolom handphone harus diisi dengan angka minimal 10 karakter dan maksimal 12 karakter contohnya 0811223345, 081122334534'
                    }
                }
            },
            shipAdd: {
                validators: {
                    notEmpty: {
                        message: 'Kolom alamat harus diisi'
                    }
                }
            },
            shipProv: {
                validators: {
                    notEmpty: {
                        message: 'Kolom provinsi harus diisi'
                    }
                }
            },
            shipCity: {
                validators: {
                    notEmpty: {
                        message: 'Kolom kota harus diisi'
                    }
                }
            },
            shipDisctrict: {
                validators: {
                    notEmpty: {
                        message: 'Kolom kecamatan harus diisi'
                    }
                }
            },
            shipPostalCode: {
                validators: {
                    notEmpty: {
                        message: 'Kolom kode pos harus diisi'
                    },
                    stringLength: {
                        min: 5,
                        max: 5,
                        message: 'Kolom kode pos harus terdiri dari lima digit'
                    },
                    digits: {
                        message: 'Kolom kode pos harus terdiri dari 0-9'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
         $('#editShipping').modal('toggle');
    });

    $(function(){
        var datepicker = $.fn.datepicker.noConflict();
        $.fn.bootstrapDP = datepicker;
        $('.datepicker').bootstrapDP({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        })
    });

    $('.extend_date').after('<span class="extend-warning" style="display:none;color:red">&nbsp;Extend Date harus lebih besar dari Current Due Date</span>');
    $('body').on('change','.btnUpdateOrder',function(){
          var currentDueDate = $(this).siblings(".due-date").html();
          var extendedDueDate = $('#extend-due-date').val();
          var a = (currentDueDate).split('/');
          var b = (extendedDueDate).split('-');
          var c = a[1]+"/"+a[0]+"/"+a[2];
          var d = b[1]+"/"+b[0]+"/"+b[2];
          //console.log(c);
          //console.log(d);
          var curDate = new Date(c);
          var extDate = new Date(d);
          // console.log('Extend By: '+extendedDueDate+' '+extDate);
          // console.log('Current: '+currentDueDate.html()+' '+curDate);
          if (curDate < extDate){
                //$(this).siblings(".due-date").html(extendedDueDate);
                $('.extend-warning').hide();
          } else {
                $('.extend-warning').show();
          }
    });


    $(".number-type").on('keypress', function(e) {
        if ( (e.which===46)||(e.which>=48&&e.which<=57) ) {
          return;
        }
        else if ( (e.which>=58&&e.which<=126)||(e.which>=33&&e.which<=45)||e.which===47 ) {
          e.preventDefault();
        }
    });


    //selected area for shipping
    $("#shipProv").change(function () {
        $.ajax({
            url: '{{ url('') }}/city/data-select/'+this.value,
            type: "GET",
            data: {
                // province_id: this.value,
            }
        }).done(function(result) {
            $('#shipCity').html(result);
            $('#shipDistrict').html("<option value = ''>Subdistrict*</option>");
        })
    });

    $("#shipCity").change(function () {
        $.ajax({
            url: '{{ url('') }}/subdistrict/data-select/'+this.value,
            type: "GET",
            data: {
                // province_id: this.value,
            }
        }).done(function(result) {
            $('#shipDistrict').html(result);
        })
    });

    //selected area for billing
    $("#billProv").change(function () {
        $.ajax({
            url: '{{ url('') }}/city/data-select/'+this.value,
            type: "GET",
            data: {
                // province_id: this.value,
            }
        }).done(function(result) {
            $('#billCity').html(result);
            $('#billDistrict').html("<option value = ''>Subdistrict*</option>");

        })
    });

    $("#billCity").change(function () {
        $.ajax({
            url: '{{ url('') }}/subdistrict/data-select/'+this.value,
            type: "GET",
            data: {
                // province_id: this.value,
            }
        }).done(function(result) {
            $('#billDistrict').html(result);
        })
    });

    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        // Layout.init(); // init current layoutf
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        //                TableEditable.init();
    });
    window.onload=function() {
        var total = 0;
        var grandtotal = 0;
        $('#order-detail').find('.shipping-fee').each(function() {
            total += parseInt($(this).val());
        });
        // GUNAKAN MASKING AGAR ADA SEPARATOR PADA TOTAL
        var totalproduksi = parseInt($('.total-biaya-produk').val());
        var getdiscount = $('.discount').text();
        var replaceRp = getdiscount.replace('Rp','');
        var replaceSpasi = replaceRp.replace(' ','');
        var replaceDot = replaceSpasi.replace(/\./g,'');
        var discount =  parseInt(replaceDot);
        var insurance = parseInt($('#insurance_price').val());

        $("#shipping-total").text(displayNumeric(total));
        grandtotal += totalproduksi;
        grandtotal += total;
        grandtotal += insurance;
        grandtotal -= discount;
        // $('.grand-total').text(displayNumeric(grandtotal));
        // $(".grand-total").text('Rp ' + parseFloat(grandtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1.").toString());
        $(".grand-total").text(displayNumeric(grandtotal));
        $(".shipping-fee").keyup(function(){
            var total = 0;
            var grandtotal = 0;
            $('#order-detail').find('.shipping-fee').each(function() {
                total += parseInt($(this).val());
            });
            // GUNAKAN MASKING AGAR ADA SEPARATOR PADA TOTAL
            var totalproduksi = parseInt($('.total-biaya-produk').val());
            var getdiscount = $('.discount').text();
            var replaceRp = getdiscount.replace('Rp','');
            var replaceSpasi = replaceRp.replace(' ','');
            var replaceDot = replaceSpasi.replace(/\./g,'');
            var discount =  parseInt(replaceDot);
            // console.log(parseinteger);
            // $("#shipping-total").text('Rp ' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1.").toString());
            $("#shipping-total").text(displayNumeric(total));
            $("#shipping-total-input").val(total);
            grandtotal += totalproduksi;
            grandtotal += total;
            grandtotal -= discount;
            // $('.grand-total').text(displayNumeric(grandtotal));
            // $(".grand-total").text('Rp ' + parseFloat(grandtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1.").toString());
            $(".grand-total").text(displayNumeric(grandtotal));
        });
    };

    $("#orderDetailAdminForm").submit(function(){
        // handle submission
        var shipping = $("#shipping-total-input").val();
        if(shipping < 10000 && $('#flagShipping').val() != 'Y'){
            return confirm("Biaya kirim lebih kecil dari 10.000 , lanjutkan ?");
        }
    });
</script>
@stop