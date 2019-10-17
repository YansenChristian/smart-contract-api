<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Terima kasih atas Pesanan Anda</title>
    <meta name="viewport" content="width=device-width"/>
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
</head>
<body style="width: 600px;margin: 0 auto;font-family: 'Noto Sans', sans-serif;font-size: 12px;color: #333333">
{{--HEADER--}}
<img src="{{ cdn('assets/images/header_ralali_email.jpg') }}" style="width: 100%;height: 73px;border-bottom: 1px dashed #ccc">
<div style="text-align: center;margin: 20px 0 25px">
    Dear <b>{{ $name }}</b>
    <h3 style="color: #ff3300">Terima Kasih atas Pesanan Anda</h3>
        <span>Berikut rincian pesanan Anda. Silahkan melakukan pembayaran<br/>
dalam <b>3x24 jam</b>, atau pesanan Anda akan secara otomatis dibatalkan.</span>
</div>
{{--BODY--}}
<table style="margin: 0 20px auto;border-radius: 3px;border-spacing: 0px;width: 95%;">
    <thead>
    {{--TABLE HEAD--}}
    {{--blue colored by default--}}

    <tr>
        <th style="font-size:14px;color:#fff;border: 1px solid #009999;border-radius:4px 4px 0 0;text-align: left;background-color: #009999;padding:10px 20px;" colspan="4"><span style="font-weight: normal">Kode Pembelian</span> {{ $order_serial }}</th>
    </tr>
    {{--ROW of Table head--}}
    <tr >
        <th style="text-align: left;font-weight: normal;vertical-align: top;padding:10px 20px;border-left:1px solid #ccc;border-bottom:1px solid #eee;">Tanggal Pembelian</th>
        <th colspan="3" style="text-align: left;border-right:1px solid #ccc;border-bottom:1px solid #eee;">{{ $order_date.' '.$order_time }}</th>
    </tr>
    {{--END ROW --}}
    {{--ROW of Table head--}}
    <tr>
        <th style="text-align: left;font-weight: normal;vertical-align: top;padding:10px 20px;border-left:1px solid #ccc;border-bottom:1px solid #eee;">Jasa Pengiriman</th>
        <th colspan="3" style="text-align: left;border-right:1px solid #ccc;border-bottom:1px solid #eee;">{{ $shipping_name }}</th>
    </tr>
    {{--END ROW --}}
    {{--ROW of Table head--}}
    <tr>
        <th style="text-align: left;font-weight: normal;vertical-align: top;padding:10px 20px;border-left:1px solid #ccc;">Alamat Pengiriman</th>
        <th colspan="3" style="border-right:1px solid #ccc;text-align: left;font-weight: normal;padding: 10px 0;">{{ $address['address'] }}<br/>
            {{ $address['subdistrict_name'] }}, {{ $address['city_name'] }}, {{ $address['province_name'] }}, {{ $address['postal_code'] }}</th>
    </tr>
    {{--END ROW--}}

    {{--SEPARATOR--}}
    {{--separator between head detail and body detail--}}
    <tr>
        <th colspan="4" style="border-left:1px solid #ccc;border-right:1px solid #ccc;padding:5px 20px;text-align: left;background-color: #f5f5f5;font-weight: bold;color:#888888">Daftar Barang <span style="font-weight: normal">({{ $total_quantity }})</span></th>
    </tr>
    </thead>

    <tbody>
    {{--PER ITEM--}}
    @foreach($data_order as $data)
        <tr>
            <td style="width:145px;padding:20px 50px 5px 20px;border-left: 1px solid #ccc">{{ $data['item_name'] }}
                <span style="font-weight: bold">(x {{ $data['product_quantity'] }})</span>
            </td>
            <td>
                <div style="font-weight: bold">Harga(Rp)</div>{{ displayNumericWithoutRp($data['price']) }}
            </td>
            <td>
                <div style="font-weight: bold">Biaya Kirim</div>
                @if($free_shipping == 'Y')
                    Gratis
                @elseif($data['shipping_price']>0)
                    {{ displayNumericWithoutRp($data['shipping_price']) }}
                @elseif($data['shipping_price'] == 0 && $free_shipping=='N')
                    Menunggu Konfirmasi CS
                @endif
            </td>
            <td style="border-right: 1px solid #ccc">
                <div style="font-weight: bold">Sub Total</div>{{ displayNumericWithoutRp( ($data['price']*$data['product_quantity']) + ($free_shipping == 'Y' ? 0 : ($data['shipping_price'] + $data['shipping_insurance']))) }}
            </td>
        </tr>
        <tr>
            <td colspan="4"  style="font-weight: bold;padding-left: 20px; padding-bottom: 20px;border-bottom: 1px dashed #ccc;border-left: 1px solid #ccc;border-right: 1px solid #ccc"> Dijual Oleh <a style="color:#fe5e33;cursor: pointer;font-weight: normal;">{{ $data['vendor_name'] }}</a>
            </td>
        </tr>
    @endforeach
    {{--END ITEM--}}

    {{--TOTAL ROW--}}
    <tr>
        <td style="border-top: 1px solid #ccc;border-left: 1px solid #ccc;"></td>
        <td colspan="3" style="border-top: 1px solid #ccc;border-right: 1px solid #ccc;">
            <table >
                <tbody>
                <tr>
                    <td style="font-weight: bold;width: 160px;padding:10px 0 5px;">Diskon</td>
                    <td style="text-align: right;padding:10px 0 5px;width: 140px;">-
                        @if($total_discount)
                            Rp {{ displayNumericWithoutRp($total_discount) }}
                        @endif</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 160px;padding-bottom: 5px;">Total Harga Barang</td>
                    <td style="padding-bottom: 5px;text-align: right;width: 140px;">Rp {{ displayNumericWithoutRp($total_price + ($free_shipping == 'Y' ? 0 : ($total_shipping_insurance + $total_shipping_price))) }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td style="border-top: 1px dashed #ccc;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;border-radius: 0px 0px 0px 4px ;"></td>
        <td style="border-bottom: 1px solid #ccc;font-size: 16px;font-weight: bold;padding: 15px 0;border-top: 1px dashed #ccc;">Total Harga</td>
        <td colspan="2" style="border-bottom: 1px solid #ccc;font-weight: bold;border-top: 1px dashed #ccc;text-align: right;padding-right:45px;font-size: 16px;color: #009999;border-right: 1px solid #ccc;border-radius: 0px 0px 4px 0px  ;">Rp {{ displayNumericWithoutRp($grandtotal+$uniq_code) }}</td>
    </tr>
    {{--END TOTAL ROW--}}
    </tbody>
</table>
{{--EXTENDED--}}

@if(isset($link))
    <div style="text-align: center;margin: 20px 0 20px;">
        Untuk memilih metode pembayaran silahkan klik <a href="{{ $link }}">disini</a>.

    </div>
@endif
{{--FOOTER--}}
<table style="border-top: 1px dashed #ccc;width: 100%;padding: 20px 0;">
    <tr>
        <td style="font-size: 10px;width: 200px;">
            <span style="font-weight: bold">PT Raksasa Laju Lintang</span><br/>
            Ruko Prominence D38 No. 51-53
            Alam Sutera Tanggerang, Banten 15143
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