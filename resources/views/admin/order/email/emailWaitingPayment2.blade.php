<!DOCTYPE html>
<html lang="id">
    <head>
        <meta https-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Terima kasih atas Pesanan Anda</title>
        <meta name="viewport" content="width=device-width"/>
        <link href='httpss://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
    </head>
    <body >
    <div style="width: 600px !important;margin: 0 auto;font-family: 'Noto Sans', sans-serif;font-size: 12px;color: #333333">
        <img src="https://static.ralali.com/assets/images/header.png" style="width: 100%;height: 73px;border-bottom: 1px dashed #ccc">
        <div style="text-align: center;margin: 20px 0 25px">
            Dear <b>{{ $name }}</b>
            </p><b>Anda</b> telah melakukan pemesanan dengan kode pemesanan <b>{{$order_serial}}</b><br>
            Mohon selesaikan proses pembayaran Anda sebelum tanggal jatuh tempo. <br><br>
            Silahkan klik link dibawah untuk melihat rincian pemesanan<br>
            <a href="{{action('Customer\CustomerController@getLoginByOrderSerial',str_replace('/', '---', $order_serial))}}" style="color: #6BB848">Pesanan Saya</a>
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
    </div>
</html>