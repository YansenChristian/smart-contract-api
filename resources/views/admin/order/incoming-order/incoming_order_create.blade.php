<?php
/*

My Note:
- weight disesuaikan dgn banyak qty
1. selesaikan final create_order v
2. tambahkan Rp. v
3. tambahkan alamat lain v
4. tambahkan cancel item

#how to get email
url variable: $urlUser
method: GET
value: email

example output:
{
    "status": 1,
    "result": [
    {
        "user_id": 1298,
        "user_name": "echo",
        "user_email": "eko34.djomi@ralali.com",
        "phone": null,
        "address": null,
        "province_id": null,
        "province_name": null,
        "city_id": null,
        "city_name": null,
        "subdistrict_id": null,
        "subdistrict_name": null,
        "postal_code": null
    },
    {
        "user_id": 1037,
        "user_name": "Eko",
        "user_email": "eko.wirama@kpjb.co.id",
        "phone": null,
        "address": null,
        "province_id": null,
        "province_name": null,
        "city_id": null,
        "city_name": null,
        "subdistrict_id": null,
        "subdistrict_name": null,
        "postal_code": null
    }
    ],
    "message": "user found"
}

#how to get item name
url variable: $urlItem
method: GET
value: name

example output:
{
    "status": 1,
    "result": [
    {
        "id": 71576,
        "name": "cermin kaca"
    },
    {
        "id": 71575,
        "name": "cermin kaca"
    },
    {
        "id": 71574,
        "name": "cermin kaca"
    },
    {
        "id": 71572,
        "name": "TES CERMIN CEMBUNG"
    }
    ],
    "message": "items found"
}

#how to get vendor name
url variable: $urlVendor
method: GET
value: name

example output:
{
    "status": 1,
    "result": [
    {
        "item_id": 71575,
        "item_name": "cermin kaca",
        "all_stock": 12,
        "lock_stock": 0,
        "sku_number": "3MAIO000055-PYA",
        "inventory_type": "finite",
        "vendor_id": 1535,
        "vendor": "PT YQA",
        "currency_dollar": null,
        "currency_id": 1,
        "start_sale": "2013-12-02 15:13:47",
        "end_sale": "2017-12-02 15:13:50",
        "weight": 1,
        "available_stock": 12,
        "rating": 0,
        "price": 100000,
        "discount": 1000,
        "discount_percentage": 99
    },
    {
        "item_id": 71574,
        "item_name": "cermin kaca",
        "all_stock": null,
        "lock_stock": 8,
        "sku_number": "3MAIO000055-RLL",
        "inventory_type": "infinite",
        "vendor_id": 1,
        "vendor": "PT. Raksasa Laju Lintang",
        "currency_dollar": 12000,
        "currency_id": 1,
        "start_sale": "2013-12-02 15:12:29",
        "end_sale": "2017-12-02 15:12:37",
        "weight": 1,
        "available_stock": 10000,
        "rating": 5,
        "price": 12000,
        "discount": 1200,
        "discount_percentage": 90
    },
    {
        "item_id": 71576,
        "item_name": "cermin kaca",
        "all_stock": 13,
        "lock_stock": 0,
        "sku_number": "3MAIO000055-PAN",
        "inventory_type": "finite",
        "vendor_id": 1534,
        "vendor": "PT ABN",
        "currency_dollar": null,
        "currency_id": 1,
        "start_sale": null,
        "end_sale": null,
        "weight": 1,
        "available_stock": 13,
        "rating": 0,
        "price": 12670,
        "discount": null,
        "discount_percentage": 0
    }
    ],
    "message": "vendors found"
}

#how to get shipping price
url variable: $urlShippingPrice
method: GET
value: province_id, shipping_id, price [required integer], weight [required integer], product_quantity [required integer]

example output:

{
    "status": 1,
    "result": {
    "shipping_price_per_kilo": 42000,
    "total_shipping_price": 42000,
    "value": {
    "province_id": 15,
    "shipping_id": 5,
    "price": 0,
    "weight": 1,
    "product_quantity": 1
    }
    },
    "message": "Shipping price found"
}

#how to send data order
url : url('admin-cp/incoming-order/create')
method: POST
value:
shipping_method
shipping_address_id //get this value from url:http://localhost/ralali3.0/public/admin-cp/incoming-order/user?email=eko.djomi@ralali.com
                    // variable: province_id (this variable content needed shipping_address_id)



#user data require
user_id //get from (#how to get email)
name: $("#name").val(),
email: $("#email").val(),
shipping_method: 5
shipping_address_id

// order detail
#need array values for order detail (loop)
item_id[]
vendor_id[]
vendor_name[]
item_name[]
product_quantity[]
price[]
discount[]
weight[]
shipping_price[]
shipping_insurance[]

// calculation
total_weight
total_discount
total_shipping_price
total_shipping_insurance
total_pricetotal_weight
total_discount
total_shipping_price
total_shipping_insurance
total_price

// promo
promo_type // using promo_name from promo-on-order-by-cms
promo_code

// payment
//if payment method selected
with_payment = 1
payment_method = Cashback
payment_id = 11
//else payment method not selected
with_payment = 0
payment_method = 0
payment_id = 0

*/
###########################

?>
@extends('layout.admin')
@section('title')
    Order Management |
@stop

@section('breadcrumb')
    <ul class="page-breadcrumb" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
        xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
        <li><a href="{{ url('admin-cp/admin') }}"> Dashboard </a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ url('admin-cp/incoming-order') }}"> Incoming Order </a><i class="fa fa-angle-right"></i></li>
        <li class="active"> Order Create </li>
    </ul>
@endsection

@section('style')
    <link type="text/css" rel="stylesheet" href="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}" />
    <link type="text/css" rel="stylesheet" href="{!! asset('assets/admin/metronic/global/plugins/jquery-ui/jquery-ui.min.css') !!}" />

    {{--<link href="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') !!}" rel="stylesheet" media="screen">--}}
    <style>
        .custom-box{border: 2px solid #EEEEEE;}

        table.table-fixed {table-layout: fixed;}
        table.table-fixed td {overflow: hidden;padding:0px;}
        tfoot {display: table-header-group;}

        .width-80 {width : 80%;}

        .table-header {background-color: #EEEEEE;}

        .dataTables_wrapper .dataTables_paginate {float: right;text-align: right;padding-top: 0.25em;display: none;}
        .dataTables_wrapper .dataTables_filter {text-align: right;padding-top: 0.25em;display:none;}
        table.table-bordered tbody td {word-break: break-word;}

        #dialog-link {
            padding: .4em 1em .4em 20px;
            text-decoration: none;
            position: relative;
        }
        #dialog-link span.ui-icon {
            margin: 0 5px 0 0;
            position: absolute;
            left: .2em;
            top: 50%;
            margin-top: -8px;
        }
        #icons {
            margin: 0;
            padding: 0;
        }
        #icons li {
            margin: 2px;
            position: relative;
            padding: 4px 0;
            cursor: pointer;
            float: left;
            list-style: none;
        }
        #icons span.ui-icon {
            float: left;
            margin: 0 4px;
        }
        .fakewindowcontain .ui-widget-overlay {
            position: absolute;
        }
        select {
            width: 200px;
        }
    </style>
@endsection

@section('content')

    <h3 class="page-title">Create Order</h3>
    <div class="panel panel-default">
        <div class="panel-heading">
            Customer Data
        </div>

        {{--@if(Session::has('msg'))
            <div class="alert alert-success">
                <p>{{ Session::get('msg') }}</p>
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif--}}

        {!! Form::open(array('action'=>'Admin\CustomerController@postCreate',  'id'=>'incomingOrderForm' , 'files'=>true)) !!}

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Email</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <input type="text" class="form-control" name="email" id="email" value="{{ Input::old('email') }}" autofocus/>
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Nama</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <input type="text" class="form-control" name="name" id="name" value="{{ Input::old('name') }}" disabled="disabled"  />
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Telepon</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <input type="text" class="form-control" name="phone" id="phone" value="{{ Input::old('phone') }}" disabled="disabled" />
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Alamat</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            {!! Form::textarea('address', '', ['class'=>'form-control', 'id'=>'address', Input::old('address'),'rows'=>3,  'required'=>'required', 'disabled'=>'disabled']) !!}
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Provinsi</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <input type="text" class="form-control" name="province" id="province" value="" disabled="disabled"  />
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Kota</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <input type="text" class="form-control" name="city" id="city" value="" disabled="disabled"  />
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <p class="pull-right">Kecamatan</p>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <input type="text" class="form-control" name="subdistrict" id="subdistrict" value="" disabled="disabled"  />
                        </div>
                    </div>
                    </br>
                    <div class="row" style="display: none;">
                        <div class="col-md-3 col-xs-3">
                            <input type="text" class="form-control" name="province_id" id="province_id" value="" disabled="disabled"  />
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <input type="text" class="form-control" name="city_id" id="city_id" value="" disabled="disabled"  />
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <input type="text" class="form-control" name="subdistrict_id" id="subdistrict_id" value="" disabled="disabled"  />
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <input type="text" class="form-control" name="postal_code" id="postal_code" value="" disabled="disabled"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">

                        </div>
                        <div class="col-md-9 col-xs-9 ">
                            <input type="checkbox" name="expandNewForm" id="checkbox-expandNewAddress"> <i>Alamat Lain</i>
                        </div>
                    </div>
                    </br>
                    <div id="addNewAddress" hidden>
                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <p class="pull-right">No. Telp</p>
                            </div>
                            <div class="col-md-9 col-xs-9">
                                <input type="text" class="form-control" name="no_telp" id="newNoTelp" />
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <p class="pull-right">Alamat</p>
                            </div>
                            <div class="col-md-9 col-xs-9">
                                {!! Form::textarea('address', '', ['class'=>'form-control', 'id'=>'newAddress', Input::old('address'),'rows'=>3]) !!}
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <p class="pull-right">Provinsi</p>
                            </div>

                            <div class="col-md-9 col-xs-9">
                                <select class="form-control" name ="province_in_profile" id="newProvince">
                                    <option value = "">Provinsi</option>
                                    @foreach($listProvince as $provinces)
                                        @if($provinces->id == Input::old('province_in_profile'))
                                            <option value = '{{$provinces->id}}' selected>{{$provinces->name}}</option>
                                        @else
                                            <option value = '{{$provinces->id}}' >{{$provinces->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <p class="pull-right">Kota</p>
                            </div>
                            <div class="col-md-9 col-xs-9">
                                <select class="form-control" name ='city_in_profile' id="newCity">
                                    <option value = ''>Kota</option>
                                    @if(Input::old('city_in_profile'))
                                        @foreach($listCity as $city)
                                            @if($city->id == Input::old('city_in_profile'))
                                                <option value = '{{$city->id}}' selected>{{$city->name}}</option>
                                            @else
                                                <option value = '{{$city->id}}' >{{$city->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <p class="pull-right">Kecamatan</p>
                            </div>
                            <div class="col-md-9 col-xs-9">
                                <select class="form-control " name = 'subdistrict_in_profile' id="newSubdistrict">
                                    <option value = ''>Kecamatan</option>
                                    @if(Input::old('subdistrict_in_profile'))
                                        @foreach($listSubdistrict as $sub)
                                            @if($sub->id == Input::old('subdistrict_in_profile'))
                                                <option value = '{{$sub->id}}' selected>{{$sub->name}}</option>
                                            @else
                                                <option value = '{{$sub->id}}' >{{$sub->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <p class="pull-right">Kode Pos</p>
                            </div>
                            <div class="col-md-9 col-xs-9">
                                <input type="text" class="form-control" name="postal_code" id="postalCode" />
                            </div>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" class="btn green" value="Create Order" id="create_order"/>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>

    <h3 class="page-title">Order Data</h3>

    <div class="row">
        <div class="col-md-12 container-content" style="overflow:auto;">
            <table class="table table-bordered table-hover">
                <thead>
                <tr class="table-header">
                    <th>Nama Barang</th>
                    <th>Vendor</th>
                    <th>SKU Number</th>
                    <th>Harga</th>
                    <th style='display:none;'>Diskon</th>
                    <th>Qty</th>
                    <th>Biaya Kirim</th>
                    <th>Sub total</th>
                    <th></th>
                </tr>
                <tr>
                    <td style="padding: 5px;"><input id="item_order" class="form-control"></td>
                    <td style="padding-right: 5px;"><select id="vendor_order" class="form-control" readonly></select></td>
                    <td style="padding-right: 5px;"><input id="sku_order" type="text" class="form-control" readonly></td>
                    <td style="padding-right: 5px;"><input id="price_order" type="text" class="form-control" readonly></td>
                    <td style="padding-right: 5px; display:none;"><input id="diskon_order_order" type="text" class="form-control" readonly></td>
                    <td style="padding-right: 5px;"><input id="quantity_order" min="1" type="number" class="form-control"></td>
                    <td style="padding-right: 5px;"><input id="shipping_order" type="text" class="form-control" readonly></td>
                    <td style="padding-right: 5px;"><input id="subtotal_order" type="text" class="form-control" readonly></td>
                    <td><button type="button" id="add_order" class="btn btn-info"><i class="fa fa-plus"></i></button></td>
                </tr>
                </thead>

                <tbody>
                    <!--<tr>
                        <td style="padding: 5px;">Nama Barang</td>
                        <td style="padding: 5px;">Vendor</td>
                        <td style="padding: 5px;">SKU Number</td>
                        <td style="padding: 5px;">Harga</td>
                        <!--<td style="padding: 5px;">Diskon</td>
                        <td style="padding: 5px;">Qty</td>
                        <td style="padding: 5px;">Biaya Kirim</td>
                        <td style="padding: 5px;">Sub total</td>
                        <td style="padding: 5px;"></td>
                     </tr> -->
                </tbody>
            </table>
        </div>
    </div><hr>

    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-6">
            <div class="col-lg-4">
                Masukkan Kode Promo
            </div>
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="promo_code">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button" id="submit_code">Gunakan</button>
                    </span>
                </div>
            </div>
            <div class="col-lg-6">
                <a class="alt-link ng-binding ng-scope" data-toggle="modal" data-target="#couponsModal" id="coupons">Anda memiliki kode promo</a>
            </div>
            <div class="col-lg-12" style="height: 10px;"></div>
            <div class="col-lg-4">
                Metode Pembayaran
            </div>
            <div class="col-lg-6">
                <select id="payment_method">
                    <option value="">-- Pilih Metode (Opsional) --</option>
                    <?php foreach($listPayment as $paymentMethod): ?>
                        <option value="{{ $paymentMethod->id  }}"><?php echo $paymentMethod->payment_name; ?></option>
                    <?php endforeach;?>

                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-4 col-xs-8">Diskon Promo</div>
                        <div class="col-lg-6 col-xs-4 text-right"><span class="total_discount">Rp. 0,-</span></div>
                        <!--<div class="col-lg-4 col-xs-8">Biaya Pengiriman</div>
                        <div class="col-lg-6 col-xs-4 text-right"><span class="total_shipping"></span></div>
                        <div class="col-lg-4 col-xs-8">Asuransi</div>
                        <div class="col-lg-6 col-xs-4 text-right"><span class="total_insurance"></span></div>-->
                        <div class="col-lg-4 col-xs-8">Grand Total</div>
                        <div class="col-lg-6 col-xs-4 text-right"><span class="grand_total">Rp. 0,-</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-footer" style="margin-top: 20px;">
        <div class="col-sm-offset-10">
            {{--<a href="{{ url('/admin-cp/incoming-order') }}" class="btn default"> Cancel</a>--}}
            <input type="button" class="btn green" value="Create Order" id="create_order"/>
        </div>
    </div>

    <!-- modal kode promo saya -->
    <div class="modal fade in" id="couponsModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title">Kode Promo Saya</h4>
                </div>
                <div class="modal-body">
                    <div class="coupon" data-ng-repeat="(k,v) in shipping.cart.promo">

                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- modal success order -->
    <div class="modal fade" id="success_order" tabindex="-1">
        <div class="modal-dialog modal-konfirm">
            <div class="modal-content eraseRad">
                <div class="modal-header">
                    <button type="button" class="close btnClose" data-dismiss="modal" aria-label="Close" id="success_close">
                        <span aria-hidden="true">
                            <span class="modal-close ico ico-close-big" data-dismiss="modal"></span>
                        </span>
                    </button>
                </div>
                <div class="modal-body modal-body-home modal-cart">
                    <div class="modal-body text-center">
                        Pemesanan dengan nomor order ID: <br>
                        <b>0027/ORD/22/02/2016</b> <br>telah berhasil.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{!! asset('assets/js/jquery.number.min.js') !!}"></script>
    <script src="{!! asset('assets/js/frontend/form-validation/formValidation.min.js') !!}"></script>
    <script src="{!! asset('assets/js/frontend/form-validation/bootstrap.min.js') !!}"></script>

    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/jquery.dataTables.columnFilter.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/admin/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}"></script>

    <script src="{!! asset('javascript/shared/customdirective.js') !!}"></script>
    <script src="{!! asset('javascript/controller/konfirmasi.ctrl.js') !!}"></script>

    <script type="text/javascript">

        $("#newProvince").change(function () {
            var valuethis = this.value;

            $.ajax({
                url: '{{ url('') }}/city/data-select/'+valuethis,
                type: "GET",
                data: {
                    // province_id: this.value,
                }
            }).done(function(result) {
                $("select[name='city_in_profile']").html(result);
                //$(".city_in_profile").html(result);
                $("select[name='subdistrict_in_profile']").html("<option value = ''>Kecamatan*</option>");
            })
        });

        $("#newCity").change(function() {
            //$(this).attr("disabled", "disabled");
            // $("select[name='subdistrict_id']").attr("disabled", "disabled");
            $.ajax({
                type: "GET",
                url: '{{ url('') }}/subdistrict/data-select/'+this.value,
                data: {
                    // province_id: this.value,
                }
            }).done(function(result) {
                $("select[name='subdistrict_in_profile']").html(result);
            })
        });

        //selected area for shipping
        $(".ship_province_id").change(function () {
            var getCityId = 'ship_city_id'+$(this).attr('id').substr(33);
            var getSubdistrictId = 'ship_subdistrict_id'+$(this).attr('id').substr(33);

            $.ajax({
                url: '{{ url('') }}/city/data-select/'+this.value,
                type: "GET",
                data: {
                    // province_id: this.value,
                }
            }).done(function(result) {
                $('#'+getCityId).html(result);
                $('#'+getSubdistrictId).html("<option value = ''>Subdistrict*</option>");

            })
        });

        $(".ship_city_id").change(function () {
            var getSubdistrictId = 'ship_subdistrict_id'+$(this).attr('id').substr(12);

            $.ajax({
                url: '{{ url('') }}/subdistrict/data-select/'+this.value,
                type: "GET",
                data: {
                    // province_id: this.value,
                }
            }).done(function(result) {
                $('#'+getSubdistrictId).html(result);
            })
        })

        //selected area for billing
        $(".bill_province_id").change(function () {
            var getCityId = 'bill_city_id'+$(this).attr('id').substr(33);
            var getSubdistrictId = 'bill_subdistrict_id'+$(this).attr('id').substr(33);

            $.ajax({
                url: '{{ url('') }}/city/data-select/'+this.value,
                type: "GET",
                data: {
                    // province_id: this.value,
                }
            }).done(function(result) {
                $('#'+getCityId).html(result);
                $('#'+getSubdistrictId).html("<option value = ''>Subdistrict*</option>");
            })
        });

        $(".bill_city_id").change(function () {
            var getSubdistrictId = 'bill_subdistrict_id'+$(this).attr('id').substr(12);

            $.ajax({
                url: '{{ url('') }}/subdistrict/data-select/'+this.value,
                type: "GET",
                data: {
                    // province_id: this.value,
                }
            }).done(function(result) {
                $('#'+getSubdistrictId).html(result);
            })
        });

        $('#checkbox-expandNewAddress').change(function(){
            if($('#checkbox-expandNewAddress').prop('checked')) {
                $("#addNewAddress").show();
            } else {
                $("#addNewAddress").hide();
            }
        });

    </script>

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
                /*{data: 'required_action', name: 'required_action'},
                {data: 'total_transaction', name: 'total_transaction', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}*/
            ],
            "order": [[ 5, "desc" ]], orderCellsTop: true,
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

        /**
         * Created by angga on 20 Jan 2016.
         */

        // customer data
        var user_id, province_id, weight, vendor_order,
                price_order, diskon_order_order, product_quantity, product_weight,
                shipping_order, subtotal_order = 0;

        var emailDataSource = [], vendorDataSource = [], promoDataSource = [], promoDataSourceResult = [];
        var all_data_order = [];

        // item  order data
        var item_id, item_order, vendor_id, vendor_order_text, sku_order = "";

        // grand total for each category data
        var total_weight_c = 0,
                total_discount_c = 0,
                total_shipping_price_c = 0,
                total_shipping_insurance_c = 0,
                total_price_c = 0;

        var order_details_c = [];

        // promo data
        var promo_name, promo_code;

        // shipping method data
        var with_payment = 0, payment_id, payment_method;

        // date sale validation
        function isValidDate(limit, dateLimit)
        {
            var now = new Date();

            // datepart
            var nowYear = now.getFullYear();
            var nowMonth = now.getMonth() + 1;
            var nowDay = now.getDate();

            // hourpart
            var nowHours = now.getHours();
            var nowMinutes = now.getMinutes();
            var nowSeconds = now.getSeconds();

            // Parse the date parts to integers
            var splitparts = dateLimit.split(" ");
            var datepart = splitparts[0].split("-");
            var year = parseInt(datepart[0], 10);
            var month = parseInt(datepart[1], 10);
            var day = parseInt(datepart[2], 10);

            var hourpart = splitparts[1].split(":");
            var hours = parseInt(hourpart[0], 10);
            var minutes = parseInt(hourpart[1], 10);
            var seconds = parseInt(hourpart[2], 10);

            // datepart validation
            if(limit == 'end'? nowYear > year : nowYear < year){
                return false;
            } else {
                if(nowYear == year){
                    if(nowMonth > month){
                        return false;
                    } else {
                        if(nowMonth == month){
                            if(limit == 'end'? nowDay > day : nowDay < day){
                                return false;
                            } else {
                                // hourspart validation
                                if(limit == 'end'? nowHours >= hours : nowHours <= hours){
                                    if(nowHours == hours)
                                        if(limit == 'end'? nowMinutes >= minutes : nowMinutes <= minutes){
                                            if(nowMinutes == minutes){
                                                if(limit == 'end'? nowSeconds >= seconds : nowSeconds <= seconds){
                                                    return false;
                                                } else {
                                                    return true;
                                                }
                                            } else{
                                                return false;
                                            }
                                        } else {
                                            return true;
                                        }
                                    else {
                                        return false;
                                    }
                                } else {
                                    return true;
                                }
                            }
                            // end of hourspart validation
                        }else{
                            return true;
                        }
                    }
                }else{
                    return true;
                }
            }
        };

        // converter price
        function toRp(amount) {
            if(amount == '-') amount = 0;
            var rev = parseInt(amount, 10).toString().split('').reverse().join('');
            var rev2 = '';
            for(var i = 0; i < rev.length; i++) {
                rev2 += rev[i];

                if(i == rev.length - 1){
                    continue;
                }
                if((i + 1) % 3 === 0){
                    rev2 += '.';
                }
            }
            return 'Rp. ' + rev2.split('').reverse().join('') + ',-';
        }

        function toString(amount){
            var rev = amount.substring(4).split('').reverse().join();
            var rev2 = '';
            for(var i = 0; i < rev.length; i++) {
                if(rev[i] === "." || rev[i] === "," || rev[i] === "-"){
                    continue;
                }
                rev2 += rev[i];
            }
            return parseInt(rev2.split('').reverse().join(''));
        }

        function toRpAll(){
            $("#price_order").val(toRp(price_order));
            $("#diskon_order_order").val(toRp(diskon_order_order));
            $("#shipping_order").val(toRp($("#shipping_order").val()));
            $("#subtotal_order").val(toRp(subtotal_order));
        }

        function toStringAll(){
            $("#price_order").val(toString($("#price_order").val()));
            $("#diskon_order_order").val(toString($("#diskon_order_order").val()));
            $("#shipping_order").val(toString($("#shipping_order").val()));
            $("#subtotal_order").val(toString($("#subtotal_order").val()));
        }

        // Hover states on the static widgets
        $( "#dialog-link, #icons li" ).hover(
                function() {
                    $( this ).addClass( "ui-state-hover" );
                },
                function() {
                    $( this ).removeClass( "ui-state-hover" );
                }
        );

        // ajax process when admin keyup character on email input
        $("#email").blur(function(){
            if($("#email").val().length > 0) {
                if (emailDataSource.indexOf($("#email").val()) === -1) {
                    alert('Email tidak terdaftar!');
                    $("#email").focus();
                } else {
                }
            }
        });
        $("#email").keyup(function(e){
            var thisValue = $(this).val();

            if(e.keyCode == 13) {
                $(this).trigger("getDataAjax_email");
            };
            $.ajax({
                url: asset('admin-cp/incoming-order/user?email=' + thisValue),
                type: "GET",
                data: {},
                success: function (data) {
                    for(var i in data.result){
                        if(emailDataSource.indexOf(data.result[i].user_email) === -1){
                            emailDataSource.push(data.result[i].user_email);
                        }
                    };
                }
            });

        }).autocomplete({
            source: emailDataSource,
            select: function(event, id){

                // when admin using click by cursor
                var thisValueClicked = $(".ui-menu-item.ui-state-focus").text();
                $.ajax({
                    url: asset('admin-cp/incoming-order/user?email='+thisValueClicked),
                    type: "GET",
                    data: {
                    },
                    success: function(data){
                        $("#name").val(data.result[0].user_name);
                        $("#phone").val(data.result[0].phone);
                        $("#address").val(data.result[0].address);
                        $("#province").val(data.result[0].province_name);
                        $("#city").val(data.result[0].city_name);
                        $("#subdistrict").val(data.result[0].subdistrict_name);
                        $("#province_id").val(data.result[0].province_id);
                        $("#city_id").val(data.result[0].city_id);
                        $("#subdistrict_id").val(data.result[0].subdistrict_id);
                        $("#postal_code").val(data.result[0].postal_code);

                        user_id = data.result[0].user_id;
                        province_id = data.result[0].province_id;
                    }
                });
            }
        });

        // when admin using enter key
        $("#email").bind("getDataAjax_email", function(e){
            // do stuff here
            var thisValue = $(this).val();

            $.ajax({
                url: asset('admin-cp/incoming-order/user?email='+thisValue),
                type: "GET",
                data: {
                },
                success: function(data){
                    $("#name").val(data.result[0].user_name);
                    $("#phone").val(data.result[0].phone);
                    $("#address").val(data.result[0].address);
                    $("#province").val(data.result[0].province_name);
                    $("#city").val(data.result[0].city_name);
                    $("#subdistrict").val(data.result[0].subdistrict_name);
                    $("#province_id").val(data.result[0].province_id);
                    $("#city_id").val(data.result[0].city_id);
                    $("#subdistrict_id").val(data.result[0].subdistrict_id);
                    $("#postal_code").val(data.result[0].postal_code);

                    user_id = data.result[0].user_id;
                    province_id = data.result[0].province_id;
                }
            });
        });

        // ajax process when admin keyup character on item input
        $("#item_order").keyup(function(e){
            var thisValue = $(this).val();

            if(e.keyCode == 13) {
                $(this).trigger("getDataAjax_item_order");
            }
            item_order = $("#item_order").val();

            $.ajax({
                url: asset('admin-cp/incoming-order/item?name='+thisValue),
                type: "GET",
                data: {},
                success: function (data) {
                    for(var i in data.result){
                        if(vendorDataSource.indexOf(data.result[i].name) === -1) {
                            vendorDataSource.push(data.result[i].name);
                        }
                    };
                    console.log("Data Item: " + vendorDataSource);
                }
            });
        }).autocomplete({
            source: vendorDataSource,
            select: function(event, id){
                // when admin using click by cursor
                var thisValueClicked = $(".ui-menu-item.ui-state-focus").text();
                $("#item_order").val(thisValueClicked);

                $.ajax({
                    url: asset('admin-cp/incoming-order/vendor?name='+thisValueClicked),
                    type: "GET",
                    data: {
                    },
                    success: function(data){
                        var vendor_length = data.result.length;
                        var html = "<option value=''>Pilih Vendor</option>";
                        for(var i=0; i<vendor_length; i++){
                            html += "<option value="+i+">"+data.result[i].vendor+"</option>";
                            $("#vendor_order").html(html).attr("readonly", false);
                        }
                    }
                });
            }
        });

        // when admin using enter key
        $("#item_order").bind("getDataAjax_item_order", function(e) {
            // do stuff here
            var thisValue = $(this).val();
            var html = "";

            $.ajax({
                url: asset('admin-cp/incoming-order/vendor?name='+thisValue),
                type: "GET",
                data: {
                },
                success: function(data){
                    var vendor_length = data.result.length;
                    var html = "<option value=''>Pilih Vendor</option>";
                    for(var i=0; i<vendor_length; i++){
                        html += "<option value="+i+">"+data.result[i].vendor+"</option>";
                        $("#vendor_order").html(html).attr("readonly", false);
                    }
                }
            });

            toRpAll();
        });

        // ajax process when admin select vendor
        $(document).on("change", "#vendor_order", function(){
            var thisValue = $("#item_order").val();
            var idValue = $(this).val();

            $.ajax({
                url: asset('admin-cp/incoming-order/vendor?name='+thisValue),
                type: "GET",
                data: {
                },
                success: function(data){
                    item_id = data.result[idValue].item_id;
                    vendor_id = data.result[idValue].vendor_id;

                    // set value
                    $("#sku_order").val(data.result[idValue].sku_number);

                    if(data.result[idValue].start_sale != null){
                        // console.log(isValidDate('start', data.result[idValue].start_sale) && isValidDate('end', data.result[idValue].end_sale));
                        if(isValidDate('start', data.result[idValue].start_sale) && isValidDate('end', data.result[idValue].end_sale)){
                            $("#price_order").val(data.result[idValue].discount);
                        } else {
                            $("#price_order").val(data.result[idValue].price);
                        }
                    }else{
                        $("#price_order").val(data.result[idValue].price);
                    }
                    $("#diskon_order_order").val(data.result[idValue].discount);

                    // get value
                    sku_order = $("#sku_order").val();
                    price_order = $("#price_order").val();
                    diskon_order_order = $("#diskon_order_order").val();
                    product_weight = data.result[idValue].weight;

                    if(sku_order == "" || sku_order == "Rp. null,-" || sku_order === "-") {
                        sku_order = "";
                        $("#sku_order").val("-")
                    } else if (price_order == "" || price_order == "Rp. null,-" || price_order === "-"){
                        price_order = 0;
                        $("#price_order").val("-");
                    }
                    else if (diskon_order_order == "" || diskon_order_order == "Rp. null,-" || diskon_order_order === "-"){
                        diskon_order_order = 0;
                        $("#diskon_order_order").val("-");
                    }

                    console.log("price_order:" +price_order+ " product_quantity:" +product_quantity+" shipping_order:" +shipping_order);
                    subtotal_order = (parseFloat(price_order) * parseInt(product_quantity)) + (parseFloat(shipping_order)); //(parseInt(price_order) * parseInt(product_quantity)) + (parseInt(diskon_order_order) * parseInt(product_quantity)) - (parseInt($("#shipping_order").val()));
                    shipping_order = parseFloat(shipping_order) + (0.35/100 * subtotal_order);
                    $("#shipping_order").val(shipping_order);
                    if(($("#province").val() == "DKI Jakarta") && (parseInt(toString($("#subtotal_order").val())) > 1000000)){
                        $("#shipping_order").val(0);
                    }
                    subtotal_order = (parseFloat(price_order) * parseInt(product_quantity)) + (parseFloat(shipping_order));

                    // get value
                    sku_order = $("#sku_order").val();
                    price_order = $("#price_order").val();
                    // diskon_order_order = $("#diskon_order_order").val();

                    // set quantity column when other columns changing
                    $("#quantity_order").val(1);
                    if(shipping_order != 0) {
                        quantity_order_func();
                    }
                    if(($("#province").val() == "DKI Jakarta") && (parseInt(toString($("#subtotal_order").val())) > 1000000)){
                        $("#shipping_order").val(0);
                    }

                    toRpAll();
                }
            });

            // get value
            vendor_order = $("#vendor_order").val();
            vendor_order_text = $("#vendor_order").find("option:selected").text();
            sku_order = $("#sku_order").val();

            toRpAll();
        });

        // recount quantity order when vendor changing
        function quantity_order_func() {
            product_quantity = $("#quantity_order").val();

            $.ajax({
                url: "{{$urlShippingPrice}}",
                type: "GET",
                data: {
                    province_id: province_id,
                    weight: weight,
                    product_quantity: product_quantity
                },
                success: function(data){
                    // console.log("total shipping: " + data.result.total_shipping_price);
                    $("#shipping_order").val(data.result.total_shipping_price);

                    if(($("#province").val() == "DKI Jakarta") && (parseInt(toString($("#subtotal_order").val())) > 1000000)){
                        $("#shipping_order").val(0);
                    }
                    shipping_order = $("#shipping_order").val();

                    if(sku_order == "" || sku_order == "Rp. null,-" || sku_order === "-") {
                        sku_order = "";
                        $("#sku_order").val("-")
                    } else if (price_order == "" || price_order == "Rp. null,-" || price_order === "-"){
                        price_order = 0;
                        $("#sku_order").val("-");
                    }
                    else if (diskon_order_order == "" || diskon_order_order == "Rp. null,-" || diskon_order_order === "-"){
                        diskon_order_order = 0;
                        $("#diskon_order_order").val("-");
                    }

                    // console.log("price_order:" +price_order+ " product_quantity:" +product_quantity+" shipping_order:" +shipping_order);
                    subtotal_order = (parseFloat(price_order) * parseInt(product_quantity)) + (parseFloat(shipping_order)); //(parseInt(price_order) * parseInt(product_quantity)) + (parseInt(diskon_order_order) * parseInt(product_quantity)) - (parseInt($("#shipping_order").val()));
                    shipping_order = (parseFloat(shipping_order) + (0.35/100 * subtotal_order));

                    $("#shipping_order").val(shipping_order);
                    if(($("#province").val() == "DKI Jakarta") && (parseInt(toString($("#subtotal_order").val())) > 1000000)){
                        $("#shipping_order").val(0);
                    }
                    subtotal_order = (parseFloat(price_order) * parseInt(product_quantity)) + (parseFloat(shipping_order));
                    if(!isNaN(subtotal_order)) $("#subtotal_order").val(subtotal_order);

                    // console.log("subtotal_order: "+subtotal_order);
                    // console.log("shipping_order: "+shipping_order);
                    toRpAll();
                }
            });

            toRpAll();
        }

        $("#quantity_order").change(function(){
            product_quantity = $("#quantity_order").val();

            $.ajax({
                url: "{{$urlShippingPrice}}",
                type: "GET",
                data: {
                    province_id: province_id,
                    weight: weight,
                    product_quantity: product_quantity
                },
                success: function(data){
                    // console.log("total shipping: " + data.result.total_shipping_price);
                    $("#shipping_order").val(data.result.total_shipping_price);

                    if(($("#province").val() == "DKI Jakarta") && (parseInt(toString($("#subtotal_order").val())) > 1000000)){
                        $("#shipping_order").val(0);
                    }

                    if(sku_order == "" || sku_order == "Rp. null,-" || sku_order === "-") {
                        sku_order = 0;
                        $("#sku_order").val("-")
                    } else if (price_order == "" || price_order == "Rp. null,-" || price_order === "-"){
                        price_order = 0;
                        $("#sku_order").val("-");
                    }
                    else if (diskon_order_order == "" || diskon_order_order == "Rp. null,-" || diskon_order_order === "-"){
                        diskon_order_order = 0;
                        $("#diskon_order_order").val("-");
                    }

                    // check this for ubnormal total shipping
                    subtotal_order = (parseFloat(price_order) * parseInt(product_quantity)) + (parseFloat(data.result.total_shipping_price)); //(parseInt(price_order) * parseInt(product_quantity)) + (parseInt(diskon_order_order) * parseInt(product_quantity)) - (parseInt($("#shipping_order").val()));
                    shipping_order = (parseFloat(data.result.total_shipping_price) + (0.35/100 * subtotal_order));

                    $("#shipping_order").val(shipping_order);
                    if(($("#province").val() == "DKI Jakarta") && (parseInt(subtotal_order) > 1000000)){
                        $("#shipping_order").val(0);
                    }
                    subtotal_order = (parseFloat(price_order) * parseInt(product_quantity)) + (parseFloat(shipping_order));
                    if(!isNaN(subtotal_order)) $("#subtotal_order").val(subtotal_order);

                    // console.log("subtotal_order: "+subtotal_order);
                    // console.log("shipping_order: "+shipping_order);
                    toRpAll();
                }
            });

            toRpAll();
        });

        // when click plus button for order detail
        var idorder = 0;
        $("#add_order").click(function(){
            if(vendorDataSource.indexOf($("#item_order").val()) === -1){
                alert('Nama Barang tidak terdaftar!');
                $("#item_order").focus();
            } else if($("#vendor_order").val() == '') {
                alert('Silahkan pilih vendor!');
            }else{
                toStringAll();

                var total_discount = 0, total_shipping = 0, total_insurance = 0;
                grand_total = 0;


                if (item_order != "" || item_order != undefined) {
                    $(this).parents("thead").next().append("<tr>" +
                            "<td>" + $("#item_order").val() + "</td>" +
                            "<td>" + vendor_order_text + "</td>" +
                            "<td>" + sku_order + "</td> " +
                            "<td>" + toRp(price_order) + "</td>" +
                            "<td style='display:none;'>" + toRp(diskon_order_order) + "</td> " +
                            "<td>" + product_quantity + "</td> " +
                            "<td>" + toRp(shipping_order) + "</td>" +
                            "<td>" + toRp(subtotal_order) + "</td>" +
                            "<td><button type='button' id='cancel_order' class='btn btn-danger' data-idorder='" + idorder + "'><i class='fa fa-times'></i></button></td>" +
                            "</tr>");
                    idorder += 1;

                    var obj = {
                        item_id: item_id,
                        vendor_id: vendor_id,
                        item_order: $("#item_order").val(),
                        vendor_order: vendor_order_text,
                        sku_order: sku_order,
                        price_order: parseInt(price_order),
                        diskon_order_order: parseInt(diskon_order_order),
                        product_quantity: parseInt(product_quantity),
                        product_weight: parseInt(product_weight),
                        shipping_order: parseInt(shipping_order),
                        subtotal_order: parseInt(subtotal_order),
                        total_insurance: (0.35 / 100 * subtotal_order)
                    };

                    all_data_order.push(obj);
                    // console.log("all_data_order_length: "+all_data_order.length);
                    // console.log(all_data_order);

                    total_weight_c = 0;
                    total_discount_c = 0;
                    total_shipping_price_c = 0;
                    total_shipping_insurance_c = 0;
                    total_price_c = 0;

                    for (var i in all_data_order) {
                        if (!isNaN(all_data_order[i].diskon_order_order)) {
                            total_discount += all_data_order[i].diskon_order_order;
                            console.log(total_discount);
                        }
                        if (!isNaN(all_data_order[i].shipping_order)) {
                            total_shipping += all_data_order[i].shipping_order;
                            console.log(total_shipping);
                        }
                        if (!isNaN(all_data_order[i].subtotal_order)) {
                            grand_total += all_data_order[i].subtotal_order;
                            console.log(grand_total);
                        }

                        // if(all_data_order[i].total_insurance) grand_total += all_data_order[i].total_insurance + total_shipping + total_discount;

                        total_weight_c += all_data_order[i].product_weight;
                        total_discount_c += all_data_order[i].diskon_order_order;
                        total_shipping_price_c += all_data_order[i].shipping_order;
                        total_shipping_insurance_c += all_data_order[i].total_insurance;
                        total_price_c += all_data_order[i].subtotal_order;

                        order_details_c[i] = {
                            item_id: all_data_order[i].item_id,
                            product_quantity: all_data_order[i].product_quantity,
                            vendor_id: all_data_order[i].vendor_id,
                            price: all_data_order[i].price_order
                        }
                    }

                    // $(".total_discount").text(toRp(total_discount));
                    // $(".total_shipping").text(toRp(total_shipping));
                    // $(".total_insurance").text(toRp(total_insurance.toFixed(2)));
                    $(".grand_total").text(toRp(grand_total.toFixed(2)));
                }

                toRpAll();

                // clear all input
                $("#item_order").val('').focus();
                $("#vendor_order").val('');
                $("#sku_order").val('');
                $("#price_order").val('');
                $("#quantity_order").val('');
                $("#shipping_order").val('');
                $("#subtotal_order").val('');

                console.log(all_data_order);
            }
        });

        // when cancel order clicked
        $(document).on('click', '#cancel_order', function(idsent){
            if(confirm('Apa anda yakin?')) {
                idsent = parseInt($(this).attr('data-idorder'));
                all_data_order.splice(idsent, 1);
                $(this).parents('tr').remove();
                idorder -= 1;

                total_weight_c = 0;
                total_discount_c = 0;
                total_shipping_price_c = 0;
                total_shipping_insurance_c = 0;
                total_price_c = 0;

                for (var i in all_data_order) {
                    // if(all_data_order[i].total_insurance) grand_total += all_data_order[i].total_insurance + total_shipping + total_discount;

                    total_weight_c += all_data_order[i].product_weight;
                    total_discount_c += all_data_order[i].diskon_order_order;
                    total_shipping_price_c += all_data_order[i].shipping_order;
                    total_shipping_insurance_c += all_data_order[i].total_insurance;
                    total_price_c += all_data_order[i].subtotal_order;

                    order_details_c[i] = {
                        item_id: all_data_order[i].item_id,
                        product_quantity: all_data_order[i].product_quantity,
                        vendor_id: all_data_order[i].vendor_id,
                        price: all_data_order[i].price_order
                    }
                }

                console.log("idorder anda: " +idorder);
                console.log(all_data_order);
            }
        });

        // when kode promo clicked!
        $("#coupons").click(function(){
            var html = "<div class='coupon-item'><h4><b>Anda Tidak Memiliki Kode Promo</b></h4></div><hr>";

            $.ajax({
                url: asset('admin-cp/promo/promo-on-order-by-cms'),
                type: "GET",
                data: {
                    total_price: total_price_c,
                    total_shipping_price: total_shipping_price_c,
                    total_shipping_insurance: total_shipping_insurance_c,
                    order_details: order_details_c ,
                    email: $('#email').val()
                },
                success: function (data) {
                    html = '';
                    for(var i in data.result.result) {
                        html += '<div class="coupon-item">' +
                                '<h4><b>' + data.result.result[i].promo_name + '</b></h4>' +
                                '<div>Masa berlaku sampai: ' + data.result.result[i].end_date +
                                '\t<button class="btn btn-info text-right" data-dismiss="modal" data-name="'+data.result.result[i].promo_name+'" data-code="'+data.result.result[i].promo_code+'" id="select-code">Pilih</button>' +
                                '</div>' +
                                '</div><hr>';
                    }

                    $("#couponsModal .coupon").html(html);
                }
            });

            $("#couponsModal .coupon").html(html);
        });

        // when promo selected
        $(document).on("click", "#select-code", function(){
            promo_code = $(this).attr("data-code");
            promo_name = $(this).attr("data-name");
            $("#promo_code").val(promo_code);
        });

        // when promo code submitted
        $("#submit_code").click(function(){
            $.ajax({
                url: asset('admin-cp/promo/promo-on-order-by-cms'),
                type: "GET",
                data: {
                    total_price: total_price_c,
                    total_shipping_price: total_shipping_price_c,
                    total_shipping_insurance: total_shipping_insurance_c,
                    order_details: order_details_c ,
                    email: $('#email').val()
                },
                success: function (data) {
                    var i;
                    for(i = 0, leng  = data.result.result.length; i < leng; i++){
                        promoDataSource.push(data.result.result[i].promo_code);
                    }
                    promoDataSourceResult = data.result.result;
                    console.log(promoDataSource);
                },
                async: false
            });
            if(promoDataSource.indexOf($("#promo_code").val()) === -1){
                alert("Kode Promo tidak tersedia");
            } else {
                toStringAll();
                var index = promoDataSource.indexOf($("#promo_code").val());
                // console.log("index :"+index);
                // console.log("promoDataSourceResult: "+promoDataSourceResult);
                promo_code = promoDataSourceResult[index].promo_code;
                promo_name = promoDataSourceResult[index].promo_name;

                $.ajax({
                    url: asset('admin-cp/incoming-order/simulation-of-promo-code'),
                    type: "GET",
                    data: {
                        total_price: total_price_c,
                        total_shipping_price: total_shipping_price_c,
                        total_shipping_insurance: total_shipping_insurance_c,
                        order_details: order_details_c,
                        email: $('#email').val(),
                        promo_code: promo_code
                    },

                    success: function (data) {
                        $(".total_discount").text(toRp(data.result.total_discount));
                        if (data.result.free_shipping) {
                            grand_total -= total_shipping_price_c;
                        }
                        grand_total -= data.result.total_discount;
                        $(".grand_total").text(toRp(grand_total.toFixed(2)));

                        console.log("grand_total: " + grand_total + " & toal_paind_needed: " + data.result.total_paid_needed);

                        toRpAll();
                    }
                });

                $("#promo_code").val('');
                toRpAll();
            }
        });

        // when shipping method selected
        $("#payment_method").change(function(){
            payment_id = $(this).val();
            if(payment_id != '') with_payment = 1;
            else with_payment = 0;
            payment_method = $(this).find("option:selected").text()
        });

        // final process
        $("#create_order").click(function(){
            toStringAll();

            var item_id = [], vendor_id = [], item_name = [], vendor_name = [],
                    product_quantity = [], price = [], discount = [], weight = [],
                    shipping_price = [], shipping_insurance = [];

            all_data_order.forEach(function(data){
                item_id.push(data.item_id),
                vendor_id.push(data.vendor_id),
                item_name.push(data.item_order),
                vendor_name.push(data.vendor_order),
                product_quantity.push(data.product_quantity),
                price.push(data.price_order),
                discount.push(data.discount),
                weight.push(data.product_weight),
                shipping_price.push(data.shipping_order),
                shipping_insurance.push(data.total_insurance)
            });

            console.log("-"+total_weight_c+"-"+total_discount_c+"-"+total_shipping_price_c+"-"+total_shipping_insurance_c+"-"+total_price_c);

            var data = {
                // customer data
                user_id: user_id,
                name: $("#name").val(),
                email: $("#email").val(),
                shipping_method: 5, // SAP shipping method
                shipping_address_id: province_id,

                // order detail data [array]
                item_id: item_id,
                vendor_id: vendor_id,
                item_name: item_name,
                vendor_name: vendor_name,
                product_quantity: product_quantity,
                price: price,
                discount: discount,
                weight: weight,
                shipping_price: shipping_price,
                shipping_insurance: shipping_insurance,

                // calculation data
                total_weight: total_weight_c,
                total_discount: toString($(".total_discount").text()),
                total_shipping_price: total_shipping_price_c,
                total_shipping_insurance: total_shipping_insurance_c,
                total_price: total_price_c,

                // promo data
                promo_type: promo_name,
                promo_code: promo_code,

                // payment data
                //if payment method selected
                with_payment: with_payment,
                payment_id: payment_id,
                payment_method: payment_method
            };

            var expandNewAddress = document.getElementById("checkbox-expandNewAddress").checked;
            data.expandNewAddress = expandNewAddress ? 1 : 0;
            if(expandNewAddress){
                data.phone = $("#newNoTelp").val();
                data.address = $("#newAddress").val();
                data.province_id = $("#newProvince").val();
                data.province_name = $("#newProvince").find("option:selected").text();
                data.city_id = $("#newCity").val();
                data.city_name = $("#newCity").find("option:selected").text();
                data.subdistrict_id = $("#newSubdistrict").val();
                data.subdistrict_name = $("#newSubdistrict").find("option:selected").text();
                data.postal_code = $("#postalCode").val();
            }else {}

            $.ajax({
                url: asset('admin-cp/incoming-order/create'),
                type: "POST",
                data: data,
                success: function(){
                    $("#success_order").modal('show');
                    console.log(data);
                }
            });

            toRpAll();
        });

        // clean all
        $("#success_close").on("click", function(){
            window.location.href = asset('admin-cp/incoming-order/create');
        });
    </script>
@endsection