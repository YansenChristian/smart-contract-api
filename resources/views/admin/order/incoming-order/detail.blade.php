@extends('admin.master')

@section('content')
<div id="container">
	@if(Session::has('success'))
	<div class="alert alert-success">
	    <p>{{ Session::get('success') }}</p>
	</div>
	@endif
	<div id="container-progress" style="text-align:center;width:100%;height:100%;display:none; background-color:rgba(0,0,0,0.7);position:absolute;top:0;left:0;z-index:99999"></div>
	<div style="width:100%;">
		<input name="reset" type="button" class="btn btn-default" onclick="location = '{{ url('admin-cp/incoming-order') }}'" value="Back" />
		{{--*/ $url = str_replace('/','---',$row->order_serial) /*--}}
		<input style="float:right;" name="reset" type="button" class="btn btn-default" onclick="location = '{{ url('admin-cp/incoming-order/update-order/'.$url) }}'" value="Update Order" />
	</div>
	<div class="panel panel-default" style="float:left; width:50%; height:200px; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style=" background:#56545B; color:white; padding:10px;">Order {{ $row->order_serial }}</div> <br>
			Order Date : {{ date('M d Y H:i:s', strtotime($row->created_at)) }} <br>
			Order Status : {{ $statusOrder }}<br>
			Purchased From :  
				@foreach($row->orderDetails as $orderDetails)
					{{ $orderDetails->user->name }} <br>
				@endforeach
			Extend : <span id="statusExtend"> {{ $row->extend_status }} </span><br>
			Due Date : <span id="dueDate"> {{ $row->due_date }} </span><br>
			Extend due date
			{!! Form::text('startDate', (isset($row->due_date)) ? to_user_date($row->due_date) : to_user_date(date("Y-m-d")), array('class' => 'form-control small required date datepicker extend_date','id' => 'startDate')) !!}
				
			<br>
		</div>
	</div>
	<div class="panel panel-default" style="float:right; width:50%; height:200px; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;">Account Information </div> <br>
			Customer Name {{ $row->name }}<br>
			Email {{ $row->email }}<br>
			Hp {{ $row->phone_number }}<br>
		</div>
	</div>
	<div class="panel panel-default" style="float:left; width:50%; height:180px; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;"> Billing Address <span style="float:right;"><a href="{{ url('admin-cp/incoming-order/edit-billing/'.$url) }}"> Edit</a></span> </div><br>
			@if(isset($billAdd))
			{{ $billAdd->name }}<br>
			{{ $billAdd->address }}<br>
			{{ @$billAdd->province_name }}, {{ @$billAdd->city_name }}, {{ @$billAdd->subdistrict_name }},  - {{ $billAdd->postal_code }}<br>
			{{ $billAdd->phone }}<br>
			@endif
		</div>
	</div>
	<div class="panel panel-default" style="float:right; width:50%; height:180px; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;">Shipping Address <span style="float:right;"><a href="{{ url('admin-cp/incoming-order/edit-shipping/'.$url) }}"> Edit</a></span> </div> <br>
			
			@if(isset($shipAdd))
			{{ $shipAdd->name }}<br>
			{{ $shipAdd->address }}<br>
			{{ @$shipAdd->province_name }}, {{ @$shipAdd->city_name }}, {{ @$shipAdd->subdistrict_name }} - {{ $shipAdd->postal_code }}<br>
			{{ $shipAdd->phone }}<br>
			@endif
		</div>
	</div>

	<div class="panel panel-default" style="float:left; width:100%;  border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
		@if(Session::has('msg'))
		    <div class="alert alert-danger">
		        <p>{{ Session::get('msg') }}</p>
		    </div>
		@endif
			<input type="hidden" id="totalDetailOrder" value="{{ count($row->orderDetails) }}"/> 
			<form id="orderDetailAdminForm"  role="form" method="POST" action="{{ url('/admin-cp/incoming-order/saved-order-detail') }}">
			<input type="hidden" name="order_serial" id="order_serial" value="{{ $row->order_serial }}" />
			<input type="hidden" name="shipping_subdistrict" value="{{@$shipAdd->subdistrict_id}}" id="shipping_subdistrict"> 
			<input type="hidden" name="shipping_city" value="{{@$shipAdd->city_id}}" id="shipping_city">			
			<div class="orderDetail" style="background:#56545B; color:white; padding:10px;">Order Detail </div> <br>
			<table class="table table-bordered">
				<tr>
					<td>Product</td>
					<td>Item Status</td>
					<td>Price</td>
					<td>Qty</td>
					<td>Discount</td>
					<td>Subtotal</td>
					<td>Vendor</td>
					<td>Jasa pengiriman</td>
					<td>Berat kirim (kg)</td>
					<td>Biaya kirim (kg)</td>
				</tr>
				{{--*/ $totalProduct = 0 /*--}}
				{{--*/ $totalShipping = 0 /*--}}
				{{--*/ $biaya = 0 /*--}}
				@foreach($row->orderDetails as $key => $orderDetail)
					<tr class="orderOrder">
						<td>{{ $orderDetail->item_name }}
						<input type="hidden" name="order_detail_id[]" id="order_detail_id" value="{{ $orderDetail->id }}" />
						</td>
						<td>
						@foreach($listStatusFlag as $statusFlag)
							@if($statusFlag->status == $orderDetail->latest_status)
								{{ $statusFlag->order_log }}
							@endif	
						@endforeach
						<input type="hidden" name="order_status[]" id="order_status" value="{{ $orderDetail->latest_status }}" />
						</td>
						<td>{{ (int) $orderDetail->price }}</td>
						<td>{{ $orderDetail->product_quantity }}</td>
						<td></td>
						<td>{{ $totalDetailOrder = $orderDetail->price*$orderDetail->product_quantity }}</td>
							{{--*/ $totalProduct = $totalProduct + $totalDetailOrder/*--}}
						<td>{{ $orderDetail->user->name }}</td>
						<td>
							<div class="order_provider_form">
								<select id="order_provider" name="order_provider[]" class="form-control order_provider">
		                        @foreach($listShipping as $keyShip => $value)
		                        	@if($value['name'] == $row->shipping->name)
		                        	<option id="selectedProvider" value="{{$value['id']}}" selected>{{$value['name']}}</option>
		                        	@else
		                            <option value="{{$value['id']}}">{{$value['name']}}</option>
		                        	@endif
		                        @endforeach
		                        </select>
		                    </div>
	                    </td>
						
						<td>
							<div class="order_weight_form">
								@if($orderDetail->weight == 0.00)
									{{--*/ $orderDetail->weight = '' /*--}}
								@endif
								<input type="text" class="{{$key}} form-control order_weight" id="order_weight" name ='order_weight[]' value="{{$orderDetail->weight}}">
							</div>
						</td>     
						<td>
							<div class="shipping_price_form">
							<input type="text" class="{{$key}} form-control shipping_price" id="shipping_price{{$key}}" name ='shipping_price[]' value="{{$orderDetail->shipping_price}}">
							<label class="lblUndefinedPrice" style="font-size:12px;color:red"> </label>
							</div>
						</td>       
						{{--*/ $totalShipping = $totalShipping + $biaya /*--}}
					</tr>
				@endforeach
				<tr>
					<td colspan="9" align="right"> Total biaya produk </td>
					<td id="productPrice">{{ $row->total_price }} </td>
				</tr>
				<tr>
					<td colspan="9" align="right"> Total biaya shipping </td>
					<td id="shippingPrice">{{ $row->total_shipping_price }} </td>
				</tr>
				<tr>
					<td colspan="9" align="right"> GRAND TOTAL </td>
					<td id="grandTotalOrder">{{ $row->grand_total }} 
					<input type="hidden" name="order_grand_total" id="order_grand_total" value="{{ $row->grand_total }}" />
					</td>
				</tr>
				<tr><td colspan="10" align="right">  
					<button type="submit" class="notifyShippingFee"> Notify User about shipping fee</button>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>
	<div class="panel panel-default" style="float:left; width:100%; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;">Payment Information </div> <br>
			<table class="table table-bordered table-hover table-payment-information">
				<thead>
					<tr>
						
						<th>
							<a href="#">Product name <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Product price <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Shipping price<span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Payment Date <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							<a href="#">Shipping payment date <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Payment Method <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Atas Nama <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							<a href="#">Product payment status <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Shipping payment status <span class="sorting-icon pull-right"></span></a>
						</th>
						<th>
							 <a href="#">Document <span class="sorting-icon pull-right"></span></a>
						</th>
					</tr>
				</thead>
				<tbody>
				{{--*/ $rowspan =  count($row->orderDetails) */--}}
					@foreach($row->orderDetails as $orderDetail)
					<tr>
						{{--*/ $flagPaid = 0 /*--}}
						{{--*/ $trf_img = @$orderDetail->shippingPayment->transfer_img /*--}}
						<td>{{ $orderDetail->item_name }}</td>
						<td>{{ (int) $orderDetail->price }}</td>
						<td>{{ $orderDetail->shipping_price }}</td>
						<td>{{ @$row->paid_at }}
							@if(@$row->paid_at)
							{{--*/ $flagPaid = 1 /*--}}
							@endif
						</td>
						<td>
							{{ @$orderDetail->shippingPayment->paid_at  }}
							
						</td>
						<td>
						{{ @$row->payments->payment_method }}
						
						</td>
						<td>
							
							{{ @$row->payments->name }}
							
						</td>
						<td>
							@if( $row->payments)
								@if($row->payments->is_verified == 'Y' && $flagPaid == 1)
									Paid
								@elseif($row->payments->is_verified == 'N' && $flagPaid == 1)
									Waiting for confirmation
								@else	
									Waiting for payment
								@endif
							@endif
						</td>
						<td>
						@if($orderDetail->shippingPayment != null)
							{{ $orderDetail->shippingPayment->shipping_payment_status }}
							
						@endif
						</td>
					

					
						<td rowspan="{{ $rowspan }}">
							@if(@$row->payments->transfer_img != null)
							<a href="{!! cdn('assets/img/bukti-transfer/').'/'.$row->payments->transfer_img !!}"> Bukti Tranfer Payment </a>
							<br><br>
							@endif
							@if($trf_img != null && $trf_img != @$row->payments->transfer_img)
							<a href="{!! cdn('assets/img/bukti-transfer/').'/'.$trf_img !!}"> Bukti Tranfer Shipping </a>
							@endif
						</td>
					</tr>
				</tbody>
	        </table>
		    

		</div>
	</div>
	<div class="panel panel-default" style="float:right; width:100%; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;">Shipping Information </div> <br>
			By : {{ @$row->shipping->name }}<br>
			@foreach($row->orderDetails as $orderDetail)
				{{ $orderDetail->item_name }} No Resi {{ $orderDetail->no_resi }} <br>
			@endforeach
			<table class="table table-bordered">
				<tr>
					<td>Product name</td>
					<td>Jasa pengiriman</td>
					<td>tanggal pengiriman</td>
					<td>Nomor resi</td>
				</tr>
				{{--*/ $totalProduct = 0 /*--}}
				{{--*/ $totalShipping = 0 /*--}}
				{{--*/ $biaya = 0 /*--}}
				@foreach($row->orderDetails as $orderDetail)
					<tr>
						<td>{{ @$orderDetail->item_name }}</td>
						<td>{{ @$row->shipping->name }}</td>
						<td>
						@if(isset($orderDetail->statusOrderDelivered))
							{{ @$orderDetail->statusOrderDelivered->created_at }}
						@endif
						</td>
						<td>{{ $orderDetail->no_resi }}</td>
						
					</tr>
				@endforeach
				
			</table>
		</div>
	</div>

	<div class="panel panel-default" style="float:left; width:50%; height:auto; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;">Comment History </div> <br>
			{!! Form::model(@$row, ['files'=>true, 'class'=>'validated']) !!}
			
				Add Order Comments &nbsp;&nbsp;&nbsp;
					{!! Form::radio('orderCmnd', 'top') !!} Term of Payment &nbsp;&nbsp;&nbsp;
					{!! Form::radio('orderCmnd', 'onepy', true) !!} One Payment <br><br>

				Status
					{!! getSelect('order_log', $statusLog, '', $options = array('class' => 'required', 'id' => $row->order_serial)) !!} <br><br>

				Item List : <label id="itemDetail[]-error" class="error" for="itemDetail[]" style="display:none;">This field is required.</label>	<br>
				<div id="itemOrder">
				@foreach($row->orderDetails()->where('delivery_status','Not Yet Complete')->get() as $orderDetail)
					@if( @$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log != 'Reject' &&
						@$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log != 'Order Received')
						
						{!! Form::checkbox('itemDetail[]', $orderDetail->id, null, ['class' => 'required checkboxItem']) !!} 
						{!! $orderDetail->item_name !!} - 
						@if(@$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log == 'Shipping Payment Confirmation')
							( Lunas )
						@else
						( {!! @$orderDetail->statusChangeLog()->orderBy('created_at', 'DESC')->first()->statusFlag->order_log !!} ) 
						@endif
						<br>
					
					@endif
				@endforeach 
				</div>
				<br> <br>

				Comment {!! Form::textarea('notes', null, ['size' => '72x6','id' => 'notes']) !!} <br><br>
				<button type="submit" class="btn btn-default" style="float:right;">Submit</button>
				{!! Form::checkbox('notify', 'Y') !!} Notify Customer by Email <br>
				{!! Form::checkbox('visible', 'Y') !!} Visible on Frontend
				{!! Form::text('orderSerial', @$row->order_serial, array('hidden' => 'hidden')) !!}
			{!! Form::close() !!}
		</div>
	</div>
	<div class="panel panel-default" style="float:right; width:50%; height:100%; border-color:white; padding:10px;">
		<div class="panel-body" style="border-color:white;">
			<div style="background:#56545B; color:white; padding:10px;">Order Totals </div> <br>
			Subtotal : {{ (int) $row->total_price }}<br>
			Shipping and Handling : {{ (int) $row->total_shipping_price }}<br>
			Discount : - <br>
			Tax : - <br>
			Grand Total : {{ (int) $row->total_price + $row->total_shipping_price }}<br>
		</div>
	</div>
</div>
@stop

@section('js')
<script src="{!! asset('assets/js/jquery.number.min.js') !!}"></script>

	<script type="text/javascript">
	

	$(".order_weight").change(function(){
		
		var weight = $(this).val();
		var provider = $('#order_provider').val();
		var district = $('#shipping_subdistrict').val();
		var city = $('#shipping_city').val();
		var rowid = $(this).attr('class');
	    //var skuClass = skuClass.substr(0,skuClass.indexOf(' '));
	    var rowid = rowid.substr(0,rowid.indexOf(' '));
		var shiprow = "shipping_price"+rowid;
		//alert(rowid);
		$("#container-progress").html("<i class='fa fa-cog fa-spin' style='margin:25% auto;font-size:60px;color:#ffffff'></i>");
        $('body').addClass('modal-open');
        window.scrollTo(0,0);
        $("#container-progress").css("display", "block");
		
		if(provider != ''){
			$.post("{{ action('Admin\Order\IncomingOrderController@postShippingPrice') }}", 
	        {
	            'order_provider':provider,
	            'order_weight': weight,
	            'shipping_city': city,
	            'shipping_subdistrict': district,
	            
	        }, function(data)
	        
	        {
	            $('body').removeClass('modal-open');
	            $("#container-progress").hide();
	            //$(this).closest('.orderOrder').find('.shipping_price').val("1111");
	            if(data.price == 'biaya belum ditentukan'){
	            	$('.notifyShippingFee').addClass('disabled');
	            	$('.notifyShippingFee').prop('disabled', true);
	            	$('.lblUndefinedPrice').html('pilih ekspedisi lain');
	            	$('#selectedProvider').val('');
	            	
	            	alert('Ubah jasa pengiriman ke ekspedisi lain');
	            }
				$('#'+shiprow).val(data.price);	

	            if(data.status == 'true')
	            {
	                $('.shipping_price').val("1111");
	                alert('tes');
	                showCart();
	                
	            }
	            else
	            {
	                $("#alert").html("<div class='alert alert-success text-center'><strong>Sorry this item is out of stock</strong></div>");
	            }
	            console.log(data);
	        });
		}else{
			 $('body').removeClass('modal-open');
            $("#container-progress").hide();
			alert('Ubah jasa pengiriman ke ekspedisi lain');
		}
	})

	$(".shipping_price").change(function(){

		var price = $(this).val();
		var productPrice = $("#productPrice").val(); 
		var totalDetailOrder = $("#totalDetailOrder").val();
		var totalItem = $('.shipping_price').length;

		if(price != 'biaya belum ditentukan'){
			$('.lblUndefinedPrice').html('');
		}
		//alert($('.shipping_price').next().val());
		//$("#shippingPrice").val();
		//$("#grandTotalOrder").val();
		
	})

	$(".extend_date").change(function(){
		var order_serial = "<?php echo $row->order_serial  ?>";
		var extend_date = $(".extend_date").val();
		$("#container-progress").html("<i class='fa fa-cog fa-spin' style='margin:25% auto;font-size:60px;color:#ffffff'></i>");
        $('body').addClass('modal-open');
        window.scrollTo(0,0);
        $("#container-progress").css("display", "block");
		
		$.post("{{ action('Admin\Order\IncomingOrderController@postExtendDueDate') }}", 
        {
            'order_serial':order_serial,
            'extend_date': extend_date
        }, function(data)
        {
            $('body').removeClass('modal-open');
            $("#container-progress").hide();
            if(data['status'] == 'Success')
            {
                $('#statusExtend').html('Yes');
                $('#dueDate').html(data.result);
                alert('extend success');
                
            }
            else
            {
                alert('due date has been extended');
            }
            console.log(data);
        });
	})

	$('#orderDetailAdminForm').formValidation({
    framework: 'bootstrap',
    trigger: 'blur',
    fields: {
      order_weight: {
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
        order_provider: {
            // The children's full name are inputs with class .childFullName
            selector: '.order_provider',
            // The field is placed inside .col-xs-6 div instead of .form-group
            row: '.order_provider_form',
            validators: {
                notEmpty:{
                    message: '<span style="color:red; font-size:12px">Kolom provider harus dipilih</span>'
                }
            }
        }
    }
  }).find('[name="order_provider"]').end();

	

	</script>
@stop