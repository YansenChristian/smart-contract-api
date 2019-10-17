@extends('admin.master')

@section('content')
<div style="width:100%;">
	<input name="reset" type="button" class="btn btn-default" onclick="location = '{{ url('admin-cp/incoming-order/view/'.$row->order_serial) }}'" value="Back" />
</div>
<div class="panel panel-default" style="float:left; width:100%; height:180px; border-color:white; padding:10px;">
	<div class="panel-body" style="border-color:white;">
		<div style="background:#56545B; color:white; padding:10px;">Item Detail <span style="float:right;"><a href="{{ url('admin-cp/incoming-order/add-product/'.$row->order_serial) }}"> Add Product</a></span></div> <br>
		<table class="table table-bordered">
			<tr>
				<td rowspan="2">Product</td>
				<td rowspan="2">Price</td>
				<td rowspan="2">Qty</td>
				<td rowspan="2">Subtotal</td>
				<td colspan="2">Discount</td>
				<td rowspan="2">Action</td>
			</tr>
			<tr>
				<td>%</td>
				<td>Rp</td>
			</tr>
			@foreach($row->orderDetails as $orderDetail)
				<tr>
					<td>{{ $orderDetail->item_name }}</td>
					<td>{{ (int) $orderDetail->price }}</td>
					<td>{{ $orderDetail->product_quantity }}</td>
					<td>{{ $orderDetail->price*$orderDetail->product_quantity }}</td>
					<td></td>
					<td></td>
					<td><a href="{{ url('admin-cp/incoming-order/view/'.$row->order_serial) }}">Remove</a></td>
				</tr>
			@endforeach
		</table>
	</div>
</div>
<div class="panel panel-default" style="float:left; width:100%; height:180px; border-color:white; padding:10px; margin-top:25px;">
	<div class="panel-body" style="border-color:white;">
		<div style="background:#56545B; color:white; padding:10px;">Apply Promo / Voucher Code</div> <br>
		{!! Form::open(array('class' => 'validated')) !!}
		{!! Form::text('promo', '', array('class' => 'form-control small required')) !!}
		<input class="btn btn-default" type="submit" value="Submit">
		{!! Form::close() !!}
	</div>
</div>
<div class="panel panel-default" style="float:left; width:100%; height:180px; border-color:white; padding:10px; margin-top:25px;">
	<div class="panel-body" style="border-color:white;">
		<div style="background:#56545B; color:white; padding:10px;">Payment Method	</div> <br>
		<input class="btn btn-default" type="button" value="Change Payment Method, Sent Email" onclick="location = '{{ url('admin-cp/incoming-order/change-payment/'.$row->order_serial) }}'">
	</div>
</div>
<div class="panel panel-default" style="float:left; width:100%; height:180px; border-color:white; padding:10px; margin-top:25px;">
	<div class="panel-body" style="border-color:white;">
		<div style="background:#56545B; color:white; padding:10px;">Shipping Method	</div> <br>
		{!! $row->shipping->name !!}
	</div>
</div>
@endsection