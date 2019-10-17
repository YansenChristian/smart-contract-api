<!DOCTYPE html>
<html lang="id">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Ralali</title>
        <meta name="viewport" content="width=device-width"/>
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
    </head>
    <body style="width: 600px;margin: 0 auto;font-family: 'Noto Sans', sans-serif;font-size: 12px;color: #333333 ">
        <img src="{{ cdn('assets/images/header_ralali_email.jpg') }}" style="width: 100%;height: 73px;border-bottom: 1px dashed #ccc">

        <div style="text-align: center;margin: 20px 0 25px">
            Dear <b>Vendor Acquisition Team,</b>
        </div>
        <table>
            <tr>
                <td colspan="3">{{ @$name }} telah berhasil terdaftar menjadi seller dengan detail sebagai berikut:</td>
            </tr>
            <tr>
                <td style="width:100px;">Nama</td>
                <td>:</td>
                <td>{{ @$name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ @$email }}</td>
            </tr>
            <tr>
                <td>Handphone</td>
                <td>:</td>
                <td>{{ @$handphone }}</td>
            </tr>
        </table>

        <table style="border-top: 1px dashed #ccc;width: 100%;padding: 20px 0;">
            <tr>
                <td style="font-size: 10px;width: 200px;">
                    <span style="font-weight: bold">PT Raksasa Laju Lintang</span><br/>
                    Ruko Prominence D38 No. 51-53
                    Alam Sutera Tanggerang, Banten 15143
                    Telp: 021-30052777
                </td>
                <td style="text-align: right">
                    <a href="http://www.facebook.com/ralalicom" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/fb.png"> </a>
                    <a href="http://twitter.com/ralalicom" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/twitter.png"> </a>
                    <a href="http://plus.google.com/+RalaliCom/about" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/g%2B.png"> </a>
                    <a href="http://www.linkedin.com/company/ralali-com" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/linkedin.png"> </a>
                    <a href="http://www.youtube.com/channel/UCA7tGuG-avOIEzcL97ybZqQ/feed" style="display: inline-block;cursor: pointer"><img src="https://static.ralali.com/assets/images/youtube.png"> </a>
                </td>
            </tr>
        </table>
    </body>
</html>
