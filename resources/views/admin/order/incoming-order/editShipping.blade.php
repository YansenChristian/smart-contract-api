@extends('admin.master')
@section('content')
	<h3> Edit Shipping Address </h3>
	{!! Form::model(@$row, ['files'=>true, 'class'=>'validated']) !!}
		<table class="table table-bordered">
			<tr>
				<td>Name <span class="required">*</span></td>
				<td>:</td>
				<td>{!! Form::text('name', @$row->name, array('class' => 'form-control required')) !!}</td>
			</tr>
			<tr>
				<td>Phone <span class="required">*</span></td>
				<td>:</td>
				<td>{!! Form::text('phone', @$row->phone, array('class' => 'form-control required')) !!}</td>
			</tr>
			<tr>
				<td>Address <span class="required">*</span></td>
				<td>:</td>
				<td>{!! Form::textarea('address', $row->address, ['size' => '72x6','class' => 'required']) !!}</td>
			</tr>
			<tr>
				<td>Province <span class="required">*</span></td>
				<td>:</td>
				<td>{!! getSelect('province', (@$provinces) ?:array(), @$row->province_id, $options = array('class' => 'form-control required')) !!}</td>
			</tr>
			<tr>
				<td>City <span class="required">*</span></td>
				<td>:</td>
				<td>{!! getSelect('city', (@$cities) ? :array(), @$row->city_id, $options = array('class' => 'form-control required')) !!}</td>
			</tr>
			<tr>
				<td>Subdistrict <span class="required">*</span></td>
				<td>:</td>
				<td>{!! getSelect('subdistrict', (@$subdistrict) ? :array(), @$row->subdistrict_id, $options = array('class' => 'form-control required')) !!}</td>
			</tr>
			<tr>
				<td>Postal Code</td>
				<td>:</td>
				<td>{!! Form::text('postal', @$row->postal_code, array('class' => 'form-control required')) !!}</td>
			</tr>
		</table>
		<input name="reset" type="reset" class="btn btn-default" onclick="location = '{{ url('admin-cp/incoming-order/view/'.Request::segment(4)) }}'" value="back" />
		<input class="btn btn-default" type="submit" value="Submit">
	{!! Form::close() !!}
	
@endsection