<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Terima kasih atas Pesanan Anda</title>
    <meta name="viewport" content="width=device-width"/>
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
</head>
<body style="width: 600px;margin: 0 auto;font-family: 'Noto Sans', sans-serif;font-size: 12px;color: #333333">
<img src="{{ cdn('assets/images/header_ralali_email.jpg') }}" style="width: 100%;height: 73px;border-bottom: 1px dashed #ccc">
<div style="text-align: center;margin: 20px 0 25px;padding: 10px;">
    Dear <b>{{$name}}</b>
    <h3 style="color: #ff3300">
        Pesanan anda dengan nomor order
        <div class="order-number black" style="font-size:24px;font-weight:bold;color:#000000;margin-bottom:10px;">{{ $order_serial }}</div>
        telah <b>kami kirim</b> dengan detail sebagai berikut.
    </h3>
    <table class="bordered-box" style="padding: 10px 20px;font-family:Arial,Helvetica;border:1px solid #cccccc;font-size:14px; width:100%;color:#676767;">
        <tr>
            <td class="col-3" style="text-align:left;vertical-align:top;width:20%;">Ekspedisi</td>
            <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
            <td class="col-8" style="text-align:left;vertical-align:top;width:71.66%;">{{ $name_ekspedisi }}</td>
        </tr>
        <tr>
            <td class="col-3" style="text-align:left;vertical-align:top;width:20%;">Nomor Resi</td>
            <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
            <td class="col-8" style="text-align:left;vertical-align:top;width:71.66%;">{{ $no_resi }}</td>
        </tr>
        <tr>
            <td class="col-3" style="text-align:left;vertical-align:left;width:20%;">Status</td>
            <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
            <td class="col-8 status" style="text-align:left;vertical-align:top;width:71.66%;color:#6BB848;font-weight:bold;">Terkirim</td>
        </tr>
        <tr>
            <td class="col-3" style="text-align:left;vertical-align:left;width:20%;">Estimasi Waktu</td>
            <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
            <td class="col-8 status" style="text-align:left;vertical-align:top;width:71.66%;color:#6BB848;font-weight:bold;">{{ $description_ekspedisi }}</td>
        </tr>
    </table>
</div>

<table style="margin: 0 auto;border-radius: 3px;border-spacing: 0px;width: 100%; padding: 10px;">
    <thead>
    <tr>
        <th style="font-size:14px;color:#fff;border: 1px solid #009999;border-radius:4px 0 0 0;text-align: left;background-color: #009999;padding:10px 20px;"><span style="font-weight: normal;margin-right: 10px;">Produk</span></th>
        <th style="font-size:14px;color:#fff;border: 1px solid #009999;border-radius:0 0 0 0;text-align: left;background-color: #009999;padding:10px 20px;"><span style="font-weight: normal;margin-right: 10px;">Harga(Rp)</span></th>
        <th style="font-size:14px;color:#fff;border: 1px solid #009999;border-radius:0 0 0 0;text-align: left;background-color: #009999;padding:10px 20px;"><span style="font-weight: normal;margin-right: 10px;">Jumlah</span></th>
        <th style="font-size:14px;color:#fff;border: 1px solid #009999;border-radius:0 0 0 0;text-align: left;background-color: #009999;padding:10px 20px;"><span style="font-weight: normal;margin-right: 10px;">Biaya Kirim</span></th>
        <th style="font-size:14px;color:#fff;border: 1px solid #009999;border-radius:0 4px 0 0;text-align: left;background-color: #009999;padding:10px 20px;"><span style="font-weight: normal;margin-right: 10px;">Subtotal(Rp)</span></th>
    </tr>
    </thead>
    <tbody>
    {{--*/ $total_shipping_price = 0; /*--}}
    {{--*/ $total_insurance = 0; /*--}}
    {{--*/ $grand_total = 0; /*--}}
    @foreach($productdetails as $productdetail)
        {{--*/ $total_shipping_price += $productdetail["shipping_price"]; /*--}}
        {{--*/ $total_insurance += $productdetail["shipping_insurance"]; /*--}}
        {{--*/ $sub_total = $productdetail["product_quantity"]*(int)$productdetail["price"]; /*--}}
        {{--*/ $grand_total += ($sub_total+$productdetail["shipping_price"]); /*--}}

        @if($no_resi == $productdetail["no_resi"])
            <tr>
                <td style="border-bottom: 1px solid #ccc;width:145px;padding:20px 50px 5px 20px;border-left: 1px solid #ccc">
                    {{ $productdetail["item_name"] }}
                </td>
                <td style="border-bottom: 1px solid #ccc;padding:20px 50px 5px 20px;">
                    Rp {{ displayNumericWithoutRp($productdetail["price"]) }}
                </td>
                <td style="border-bottom: 1px solid #ccc;padding:20px 50px 5px 20px;">
                    {{ $productdetail["product_quantity"] }}
                </td>
                <td style="border-bottom: 1px solid #ccc;padding:20px 50px 5px 20px;">
                    @if($productdetail['shipping_price'] == 0) Gratis
                    @else {{ displayNumeric($productdetail['shipping_price']) }} @endif
                </td>
                <td style="border-bottom: 1px solid #ccc;padding:20px 30px 5px 20px;border-right: 1px solid #ccc">
                    {{ displayNumeric($sub_total + $productdetail['shipping_price']) }}
                </td>
            </tr>
        @endif
    @endforeach

    <!--<tr>
        <td style="border-top: 1px solid #ccc;border-left: 1px solid #ccc;"></td>
        <td colspan="3" style="border-top: 1px solid #ccc;border-right: 1px solid #ccc;">
            <table >
                <tbody>
                <tr>
                    <td style="font-weight: bold;width: 160px;padding-bottom: 5px;padding-top: 10px;">Kode Unik</td>
                    <td style="padding-bottom: 5px;text-align: right;width: 140px;">{{ @$uniq_code }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>-->
    <tr>
        <td style="border-top: 1px dashed #ccc;border-left:1px solid #ccc"></td>
        <td colspan="4" style="border-top: 1px dashed #ccc;border-right:1px solid #ccc">
            <tr>
                <td style="border-left:1px solid #ccc"></td>
                <td colspan="2" style="font-weight: bold;width: 160px;padding:10px 0 5px;">Total Diskon</td>
                <td colspan="2" style="text-align: right;padding:10px 45px 5px 0;border-right:1px solid #ccc"> -
                    @if(isset($discount) && $discount != 0)
                        {{--*/ $discount_order = round($discount/ 100 * ($grand_total - $total_shipping_price)) /*--}}
                        Rp {{ displayNumericWithoutRp($discount_order) }}
                    @else
                        {{--*/ $discount_order = $total_discount /*--}}
                        Rp {{ displayNumericWithoutRp($discount_order) }}
                    @endif
                </td>
            </tr>
        </td>
    </tr>
    <!--tr>
        <td style="border-left:1px solid #ccc"></td>
        <td colspan="4" style="border-right:1px solid #ccc">
            <tr>
                <td style="border-left:1px solid #ccc"></td>
                <td colspan="2" style="font-weight: bold;width: 160px;padding-bottom: 5px;">Total Asuransi</td>
                <td colspan="2" style="text-align: right;padding:10px 45px 5px 0;border-right:1px solid #ccc">
                    Rp {{ displayNumericWithoutRp($total_insurance) }}
                </td>
            </tr>
        </td>
    </tr-->
    @if($additional_fee)
    <tr>
        <td style="border-left:1px solid #ccc"></td>
        <td colspan="4" style="border-right:1px solid #ccc">
            <tr>
                <td style="border-left:1px solid #ccc"></td>
                <td colspan="2" style="font-weight: bold;width: 160px;padding-bottom: 5px;">Biaya Tambahan</td>
                <td colspan="2" style="text-align: right;padding:10px 45px 5px 0;border-right:1px solid #ccc">
                    Rp {{ displayNumericWithoutRp($additional_fee) }}
                </td>
            </tr>
        </td>
    </tr>
    @endif
    <tr>
        <td style="border-top: 1px dashed #ccc;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;border-radius: 0px 0px 0px 4px ;"></td>
        <td style="border-bottom: 1px solid #ccc;font-size: 16px;font-weight: bold;padding: 15px 0;border-top: 1px dashed #ccc;">Total Harga</td>
        <td colspan="3" style="border-bottom: 1px solid #ccc;font-weight: bold;border-top: 1px dashed #ccc;text-align: right;padding-right:45px;font-size: 16px;color: #009999;border-right: 1px solid #ccc;border-radius: 0px 0px 4px 0px  ;">
            {{ displayNumeric($grand_total - $discount_order + $total_insurance + $additional_fee) }}
        </td>
    </tr>
    </tbody>
</table>

<table class="bordered-box margined inverted" style=" margin-top:20px;border:1px solid #cccccc;width:100%;padding:15px;font-family:Arial;color:#676767">
    {{--*/ $objJSON = json_decode($shipping_address,true) /*--}}
    <tr >
        <td colspan="3" class="col-12" style="vertical-align:top;width:100%;font-size:16px;"><b>Informasi Pembayaran</b></td>
    </tr>
    <tr >
        <td class="col-3" style="vertical-align:top;width:25%;font-size:14px;">Nama Penerima</td>
        <td class="col-1" style="vertical-align:top;width:8.33%;font-size:14px;">:</td>
        <td class="col-8 " style="vertical-align:top;width:66.66%;font-size:14px;">{{$objJSON['name']}}</td>
    </tr>
    <tr >
        <td class="col-3" style="vertical-align:top;width:25%;font-size:14px;">Alamat Penerima</td>
        <td class="col-1" style="vertical-align:top;width:8.33%;font-size:14px;">:</td>
        <td class="col-8 " style="vertical-align:top;width:66.66%;font-size:14px;">{{$objJSON['address']}}</td>
    </tr>
    <tr >
        <td class="col-3" style="vertical-align:top;width:25%;font-size:14px;">Nomor Hp</td>
        <td class="col-1" style="vertical-align:top;width:8.33%;font-size:14px;">:</td>
        <td class="col-8 " style="vertical-align:top;width:66.66%;font-size:14px;">{{$objJSON['phone']}}</td>
    </tr>
    <tr >
        <td class="col-3" style="vertical-align:top;width:25%;font-size:14px;">Email</td>
        <td class="col-1" style="vertical-align:top;width:8.33%;font-size:14px;">:</td>
        <td class="col-8 " style="vertical-align:top;width:66.66%;font-size:14px;">{{$email}}</td>
    </tr>
</table>

<table style="border-top: 1px dashed #ccc;width: 100%;padding: 20px 0;">
    <tr>
        <td style="font-size: 10px;width: 200px;">
            <span style="font-weight: bold">PT Raksasa Laju Lintang</span><br/>
            Ruko Prominence D38 No. 51-53
            Alam Sutera Tangerang, Banten 15143
            Telp: 021-30052777
        </td>
        <td style="text-align: right">
            <a href="https://www.facebook.com/ralalicom" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/fb.png"> </a>
            <a href="https://twitter.com/ralalicom" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/twitter.png"> </a>
            <a href="https://plus.google.com/+RalaliCom/about" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/g+.png"> </a>
            <a href="https://www.linkedin.com/company/ralali-com" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/linkedin.png"> </a>
            <a href="https://www.youtube.com/channel/UCA7tGuG-avOIEzcL97ybZqQ/feed" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/youtube.png"> </a>

        </td>
    </tr>

</table>
</body>
</html>