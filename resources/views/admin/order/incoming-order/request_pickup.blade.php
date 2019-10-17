<!DOCTYPE html>
<html lang="id">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Permintaan Pengambilan barang oleh Jasa Ekspedisi Pengiriman</title>
        <meta name="viewport" content="width=device-width"/>
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
    </head>
    <body style="width: 600px;margin: 0 auto;font-family: 'Noto Sans', sans-serif;font-size: 12px;color: #333333">
        <img src="{{ cdn('assets/images/header_ralali_email.jpg') }}" style="width: 100%;height: 73px;border-bottom: 1px dashed #ccc">
        <div style="text-align: center;margin: 20px 0 25px;padding: 10px;">
            Dear <b>{{ $shipping->name }}</b>
            <h3 style="color: #ff3300">
                {{ $merchant['name'] }} telah menyiapkan pesanan dengan nomor order
                <div class="order-number black" style="font-size:24px;font-weight:bold;color:#000000;margin-bottom:10px;">{{ $order_serial }}</div>
                pada hari{{ date('d-m-Y') }} pukul {{ date('H:i:s') }}.
                <p>
                    Mohon Melakukan penjemputan barang pada alamat di bawah ini
                </p>
            </h3>
            <table class="bordered-box" style="padding: 10px 20px;font-family:Arial,Helvetica;border:1px solid #cccccc;font-size:14px; width:100%;color:#676767;">
                <tr>
                    <td class="col-3" style="text-align:left;vertical-align:top;width:20%;">Alamat</td>
                    <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
                    <td class="col-8" style="text-align:left;vertical-align:top;width:71.66%;">
                        {{ $storehouse->address }}
                        <p>
                            {{ $storehouse->subdistrict_name }}, {{ $storehouse->city_name }}, {{ $storehouse->province_name }},
                            {{ $storehouse->postal_code  }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="col-3" style="text-align:left;vertical-align:top;width:20%;">Penanggung Jawab</td>
                    <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
                    <td class="col-8" style="text-align:left;vertical-align:top;width:71.66%;">{{ $storehouse->name }}</td>
                </tr>
                <tr>
                    <td class="col-3" style="text-align:left;vertical-align:top;width:20%;">Nomor Telepon</td>
                    <td class="col-1" style="text-align:left;vertical-align:top;width:3.33%;">:</td>
                    <td class="col-8 status" style="text-align:left;vertical-align:top;width:71.66%;color:#6BB848;font-weight:bold;">{{ $storehouse->phone }}</td>
                </tr>
            </table>
            Terlampir <b>dokumen</b> detail barang pesanan
        </div>

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