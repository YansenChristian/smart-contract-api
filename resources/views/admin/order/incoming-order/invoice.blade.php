<!DOCTYPE html>
<html>
<head>
    <title>Print Delivery Order</title>
    {{--<link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>--}}

    <!-- JS -->
    <style  type="text/css">
        html{margin: 0;}
        body{}
    </style>
</head>

<body style="color: #333333;font-family: arial, helvetica, sans-serif">
<div style="background-color: #ff5700; width: 843px; height: 100px; background-size: cover; width: 100%; margin-bottom: 25px;">
    <img src="{{ cdn('assets/img/microsite/logo/'.$do->logo) }}" height="60" style="padding: 20px;border-radius: 25%">
    <span class="move" style="font-size: 30px; color: white; position: absolute; left: 30px;font-family: arial, helvetica, sans-serif;line-height: 75px;margin-left: 75px;"><strong>{{ @$do->name_shop }}</strong></span>
    <span class="order" style="position: absolute;font-family: arial, helvetica, sans-serif;right: 40px;font-size: 25px;font-weight: bold;color: white;line-height: 75px;">Invoice</span>
</div>
<div style="background-image: url('{{ cdn('assets/img/background-email.jpg') }}'); width: 843px; height: 69px; background-size: cover; width: 100%; margin-bottom: 40px;">
    <span class="move" style="font-size: 30px; color: white; position: absolute; top: 20px; left: 30px;font-family: arial, helvetica, sans-serif">
        <img width="200px" src="{{ cdn('assets/images/ralali_white_logo.png') }}">
    </span>
    <span class="order" style="position: absolute; right: 100px; top: 20px; font-size: 30px; color: white">Invoice</span>
</div>
<table class="table" style="margin: 0px 20px;">
    <tr>
        <td style="padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif" colspan="70">
            Dear <b>{{ $order_desc->name }}</b><br>
            Berikut Invoice Anda dengan <b>Nomor Order {{ $order_desc->order_serial }}</b>
        </td>
    </tr>
    <tr>
        <td style="padding-right: 5px; padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif">Nomor Invoice</td>
        <td style=" padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif;width: 5px">:</td>
        <td style="padding-left: 5px; padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif"><strong>{{ $order_desc->invoice->invoice_serial_id }}</strong></td>
    </tr>
    <tr>
        <td style="padding-right: 5px; padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif">Tanggal</td>
        <td style="padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif;width:5px">:</td>
        <td style="padding-left: 5px; padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif"><strong>{{ date("d F Y H:i") }}</strong></td>
    </tr>
    <tr>
        <td style="padding-right: 5px; padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif">Metode Pembayaran</td>
        <td style="padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif;width:5px">:</td>
        <td style="padding-left: 5px; padding-bottom: 5px; font-size: 12px; font-family: arial, helvetica, sans-serif"><strong>{{ $payment_method }}</strong></td>
    </tr>
</table>
<div class="list-produk" style="margin: 0 30px;">
    <table  class="local" style="width: 100%; font-size: 12px; font-family: arial, helvetica, sans-serif; margin-top: 30px; border: 1px solid #ccc;border-spacing: 0px;border-radius: 4px;">
        <tr class="order-list">
            <th style="text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc"><strong>Nama Barang</strong></th>
            <th style="text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc"><strong>Harga(Rp)</strong></th>
            <th style="text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc"><strong>Berat(Kg)</strong></th>
            <th style="text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc"><strong>Biaya Kirim</strong></th>
            <th style="text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc"><strong>Penjual</strong></th>
            <th style="text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #ccc"><strong>Sub Total (Rp)</strong></th>
        </tr>
        {!! $items !!}
        <!--<tr>
            <td style="border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px"></td>
            <td style="border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px"></td>
            <td style="border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px"></td>
            <td colspan="2" style="padding-left: 20px;font-weight: bold;border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px">Biaya Kirim</td>
            <td style="text-align: right;padding-right: 15px;border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px">
                {{ $total_shipping_price }}
            </td>
        </tr>-->
        <tr>
            <td style="border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px"></td>
            <td style="border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px"></td>
            <td style="border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px"></td>
            <td colspan="2" style="padding-left: 20px;font-weight: bold;border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px">Total Diskon</td>
            <td style="text-align: right;padding-right: 15px;border-top: 1px solid #cccccc;padding-top: 15px;padding-bottom: 2px">
                {{ $discount }}
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;"></td>
            <td style="padding-top: 10px;"></td>
            <td style="padding-top: 10px;"></td>
            <td colspan="2" style="padding-left: 20px;font-weight: bold;padding-top: 10px;">Total Asuransi</td>
            <td style="text-align: right;padding-right: 15px;padding-top: 10px;">
                {{ $total_shipping_insurance }}
            </td>
        </tr>
        <!--<tr>
            <td style="padding-top: 15px;padding-bottom: 2px"></td>
            <td style="padding-top: 15px;padding-bottom: 2px"></td>
            <td style="padding-top: 15px;padding-bottom: 2px"></td>
            <td colspan="2" style="padding-left: 20px;font-weight: bold;padding-top: 15px;padding-bottom: 2px">Kode Bayar</td>
            <td style="text-align: right;padding-right: 15px;padding-top: 15px;padding-bottom: 2px">
                {{ $random_digit }}
            </td>
        </tr>-->
        <tr>
            <td style="padding-top: 10px;padding-bottom: 10px;"></td>
            <td style="padding-top: 10px;padding-bottom: 10px;"></td>
            <td style="padding-top: 10px;padding-bottom: 10px;"></td>
            <td colspan="2" style="padding-left: 20px;font-weight: bold;padding-top: 10px;padding-bottom: 10px;">Total Harga</td>
            <td style="text-align: right;padding-right: 15px;padding-top: 10px;padding-bottom: 10px;font-size: 16px;font-weight: bold;">
                {{ $terbilang }}
            </td>
        </tr>
        <!--<tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3">
                <table>

                </table>
            </td>
        </tr>-->
    </table>
    <div class="destination" style="width: 100%; margin-top: 30px;font-size: 12px">
        <table style="border: 1px solid #ccc;width: 100%;border-spacing: 0px;">
            <tr>
                <td style="padding: 15px 20px;width: 50%;border-right: 1px solid #ccc;">
                    @if($shipping_address)
                    <table style="border-spacing: 0px;">
                        <tr>
                            <td colspan="3" style="font-weight: bold;font-size: 14px;">Alamat Pengiriman</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;width: 80px;">Penerima </td>
                            <td style="vertical-align: top;width: 5px;">:</td>
                            <td style="font-weight: bold;vertical-align: top;">{{ $shipping_address['name'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;width: 80px;">Handphone</td>
                            <td style="vertical-align: top;width: 5px;">:</td>
                            <td style="font-weight: bold;vertical-align: top;">{{ $shipping_address['phone'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;width: 80px;">Alamat</td>
                            <td style="vertical-align: top;width: 5px;">:</td>
                            <td style="font-weight: normal;vertical-align: top;">
                                {{ $shipping_address['address'] }}<br/>
                                {{ $shipping_address['province'] }}, {{ $shipping_address['city'] }} {{ $shipping_address['postal_code'] }}
                            </td>
                        </tr>
                    </table>
                    @endif
                </td>
                <td>
                    @if($billing_address)
                    <table style="padding: 15px 20px;border-spacing: 0px;">
                        <tr>
                            <td colspan="3" style="font-weight: bold;font-size: 14px;">Alamat Penagihan</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;width: 80px;">Penerima </td>
                            <td style="vertical-align: top;width: 5px;">:</td>
                            <td style="font-weight: bold;vertical-align: top;">{{ $billing_address['name'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;width: 80px;">Handphone</td>
                            <td style="vertical-align: top;width: 5px;">:</td>
                            <td style="font-weight: bold;vertical-align: top;">{{ $billing_address['phone'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;width: 80px;">Alamat</td>
                            <td style="vertical-align: top;width: 5px;">:</td>
                            <td style="font-weight: normal;vertical-align: top;">
                                {{ $billing_address['address'] }}<br/>
                                {{ $billing_address['province'] }}, {{ $billing_address['city'] }} {{ $billing_address['postal_code'] }}
                            </td>
                        </tr>
                    </table>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="shipment" style="text-align: center; padding-top: 20px; font-style: italic; padding-bottom: 20px;font-size: 12px;">
        <span><b>Terima kasih Anda telah berbelanja di Ralali.com</b></span>
    </div>
    <div class="dashed" style="border: 1px dashed #ccc; width: 100%"></div>
    <div class="icon" style="position: relative; left: 600px; width: 100%; top: 50px">
        <img width="100px" src="{{ cdn('assets/images/ralali-logo.png') }}">
    </div>
    <div class="block" style="font-size: 10px; width: 100%; padding-bottom: 15px; padding-top: 10px">
        <p><strong>PT Raksasa Laju Lintang</strong></p>
        <p>Ruko Prominence D38 No. 51-53 Alam Sutera Tangerang, Banten 15143</p>
        <p>Telp: 021-30052777</p>
    </div>
    <div class="line-color">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

</body>
</html>