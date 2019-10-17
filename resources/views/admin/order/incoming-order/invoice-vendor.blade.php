<!DOCTYPE html>
<html>
<head>
    <title>Print Invoice</title>
    {{--<link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>--}}

    <!-- JS -->
    <style  type="text/css">
            /* cyrillic-ext */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/C7bP6N8yXZ-PGLzbFLtQKRJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
    }
    /* cyrillic */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/iLJc6PpCnnbQjYc1Jq4v0xJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    /* devanagari */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/5pCv5Yz4eMu9gmvX8nNhfRJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+02BC, U+0900-097F, U+1CD0-1CF6, U+1CF8-1CF9, U+200B-200D, U+20A8, U+20B9, U+25CC, U+A830-A839, U+A8E0-A8FB;
    }
    /* greek-ext */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/gEkd0pn-sMtQ_P4HUpi6WBJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+1F00-1FFF;
    }
    /* greek */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/iPF-u8L1qkTPHaKjvXERnxJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+0370-03FF;
    }
    /* vietnamese */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/mTzVK0-EJOCaJiOPeaz-hxJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
    }
    /* latin-ext */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/erE3KsIWUumgD1j_Ca-V-xJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
      unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
    }
    /* latin */
    @font-face {
      font-family: 'Noto Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Noto Sans'), local('NotoSans'), url(https://fonts.gstatic.com/s/notosans/v6/LeFlHvsZjXu2c3ZRgBq9nFtXRa8TVwTICgirnJhmVJw.woff2) format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215;
    }
        /*#invoiceHeader{
            text-align:center;
            padding:20px 0;
            border-bottom:1px solid #cccccc;
        }*/

        /*.openWords{
            text-align:center;
            font-size:16px;
            color:#676767 !important;
            padding:20px 0 10px 0;
        }*/

        /*.orderNumber{
            font-size:24px;
        }*/

        /*.text-dark{
            color:#333333 !important;
        }*/

        /*.text-grey{
            color:#676767 !important;
        }*/

        /*.informationDetail .border{
            padding:10px 15px;
            margin-bottom:20px;
        }*/

        /*.informationDetail .table{
            margin-bottom:0px;
        }*/

        /*.informationDetail .table td{
            border-top:none;
            vertical-align:top;
            padding-top:0px;
            padding-bottom:0px;
        }*/

        /*#footerInvoice{
            text-align:center;
            border-top:1px solid #cccccc ;
            padding-top:30px;
        }*/

        /*#content.printAble{
            width:800px;
            margin:0 auto;
        }*/

        /*.tableInvoice .table > thead > tr >th{
            background-color:#666666 !important;
            color:#ffffff !important;
            font-weight:normal;
            border-bottom:1px solid #666666;
            padding:15px 20px;
        }*/

        /*.tableInvoice .row > *{
            padding-left:0;
            padding-right:0;
        }*/

        /*.tableInvoice .table > tbody > tr > td{*/
        /*padding:20px;*/
        /*color:#676767 !important;*/
        /*}*/

        /*.tableInvoice .table > tbody > tr.assignment > td{*/
        /*padding:0;*/
        /*padding-top:40px;*/
        /*margin-left:-15px;*/
        /*margin-right:-15px;*/
        /*color:#676767 !important;*/
        /*}*/

        /*.tableInvoice .table > tbody > tr.assignment > td + td{*/
        /*padding-left:15px;*/
        /*}*/

        /*.checkoutLabel{*/
        /*}*/

        /*.total{*/
        /*color:#000000 !important;*/
        /*}*/

        /*.finalRowInvoice td{*/
        /*background-color:#d0d1d2 !important;*/
        /*padding:20px 20px 5px 20px !important;*/
        /*}*/

        /*.finalRowInvoice.terbilang td{*/
        /*padding:0px 20px 20px 20px !important;*/
        /*border-top:0;*/
        /*}*/

        /*.assignPlate{*/
        /*height:60px;*/
        /*border:1px solid #cccccc;*/
        /*}*/

        /*.assignLabel{*/
        /*color:#676767 !important;*/
        /*}*/

        /*.assignee{*/
        /*color:#676767 !important;*/
        /*}*/
        html{margin: 0;}
        body{}

    </style>
</head>

<body style="color: #444;font-family: 'Noto Sans', sans-serif;">
<div style="background-color: #ff5700; width: 843px; height: 100px; background-size: cover; width: 100%; margin-bottom: 25px;">
    <img src="{{ cdn('assets/img/microsite/logo/'.@$vendor_location->logo) }}" style="padding: 20px;border-radius: 25%; width:60px; height:60px;">
    <span class="move" style="font-size: 24px; color: white; position: absolute; left: 30px;line-height: 75px;margin-left: 75px;"><strong>{{ @$vendor_location->name_shop }}</strong></span>
    <span class="order" style="letter-spacing: 1px; position: absolute;right: 40px;font-size: 16px;font-weight: bold;color: white;line-height: 75px;">INVOICE</span>
</div>
{{-- <div style="background-image: url('http://static.ralali.com/assets/img/background-email.jpg'); width: 843px; height: 69px; background-size: cover; width: 100%; margin-bottom: 40px;">
    <span class="move" style="font-size: 30px; color: white; position: absolute; top: 20px; left: 30px;font-family: arial, helvetica, sans-serif"><strong>{{ @$vendor_location->name_shop }}</strong></span>
    <span class="order" style="position: absolute; right: 100px; top: 20px; font-size: 30px; color: white">Invoice</span>
</div> --}}
<table class="table" style="padding: 0 20px; font-size: 13px; border-spacing: 0px;">
    <tr>
        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px;">Nomor Invoice</td>
        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px;">:</td>
        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;"><strong>{{ @$invoice_vendor->invoice_serial_id }}</strong></td>
    </tr>
    <tr>
        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px;">Tanggal</td>
        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px;">:</td>
        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;"><strong>{{ date('d-M-Y H:i:s', strtotime($invoice_vendor->created_at)) }}</strong></td>
    </tr>
    @if($vendor_location->province)
    <tr>
        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px">Lokasi Penjual</td>
        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px;">:</td>
        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;"><strong>{{ $vendor_location->province['name']  }}</strong></td>
    </tr>
    @endif
    <tr>
        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px;">Metode Pembayaran</td>
        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px;">:</td>
        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;"><strong>{{ @$payment->payment_method  }}</strong></td>
    </tr>
</table>
<div class="list-produk" style="margin: 0 30px;">
    <table class="local" style="width: 100%; font-size: 12px; margin-top: 30px; border: 1px solid #e6e6e6;border-collapse: collapse;">
        <tr class="order-list">
            <th style="background-color: #f6f6f6; font-weight: bold; color: #b6b6b6; text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #e6e6e6;"><strong>NAMA BARANG</strong></th>
            <th style="background-color: #f6f6f6; font-weight: bold; color: #b6b6b6; text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #e6e6e6;"><strong>HARGA</strong></th>
            <th style="background-color: #f6f6f6; font-weight: bold; color: #b6b6b6; text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #e6e6e6;"><strong>BERAT</strong></th>
            <th style="background-color: #f6f6f6; font-weight: bold; color: #b6b6b6; text-align: left; padding:15px 0px 15px 15px; font-size: 12px;border-bottom: 1px solid #e6e6e6"><strong>SUBTOTAL</strong></th>
        </tr>
        {!! $items !!}

        {{--<tr>
            <td style="border-top:1px solid #e6e6e6"></td>
            <td style="border-top:1px solid #e6e6e6"></td>
            <td style="font-weight: bold;border-top:1px solid #e6e6e6;padding:15px 0px 15px 15px">Total Diskon</td>
            <td style="font-weight: bold;text-align: left;border-top:1px solid #e6e6e6;padding:15px 0px 15px 15px;">Rp {{ displayNumericWithoutRp($order_desc->discount) }}</td>
        </tr>--}}
        @if(displayNumericWithoutRp($total_insurance) != 0)
        <tr>
            <td ></td>
            <td ></td>
            <td style="font-weight: bold;padding:15px 0px 15px 15px">Total Asuransi</td>
            <td style="font-weight: bold;text-align: left;padding:15px 0px 15px 15px;">Rp {{ displayNumericWithoutRp($total_insurance) }}</td>
        </tr>
        @endif
        <tr>
            <td style="font-weight: bold;padding:15px 0px 15px 15px">Jasa Pengiriman: <span style="color: #ff5000;">{{ $shipping_service->name }}</span></td>
            <td></td>
            <td style="font-weight: bold;padding:15px 0px 15px 15px">Biaya Pengiriman</td>
            <td style="font-weight: bold;text-align: left;padding:15px 0px 15px 15px;">Rp {{ displayNumericWithoutRp($total_shipping)}}</td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #e6e6e6"></td>
            <td style="border-top: 1px solid #e6e6e6"></td>
            <td style="font-weight: bold;padding:15px 0px 15px 15px;color: #42b449;font-size: 16px;border-top: 1px solid #e6e6e6">Total</td>
            <td style="font-weight: bold;text-align: left;padding:15px 0px 15px 15px;color: #42b449;font-size: 16px;border-top: 1px solid #e6e6e6">Rp {{ displayNumericWithoutRp(($gtotal + $total_shipping + $total_insurance)) }}</td>
        </tr>

    </table>

    <div class="destination" style="margin-top: 30px;font-size: 12px">
        <table style="width: 100%; border-spacing: 0;">
            <tr>
                <td style="width: 45%; vertical-align: top;">
                    <table style="border: 1px solid #e6e6e6; width: 100%; border-collapse: collapse; padding: 0;">
                        <tr>
                            <td colspan="2" style="background-color: #f6f6f6; color:#747474; font-weight: bold; font-size: 14px; padding:10px; text-align: left;">Alamat Pengiriman</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">Penerima </td>
                            <td style="font-weight: bold;vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">{{ $shipping_address['name'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">Handphone</td>
                            <td style="font-weight: bold;vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">{{ $shipping_address['phone'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">Alamat</td>
                            <td style="font-weight: normal;vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">
                                @if($shipping_address)
                                    {{ $shipping_address['address'] }}<br/>
                                    {{ $shipping_address['province'] }}, {{ $shipping_address['city'] }} {{ @$shipping_address['postal_code'] }}<br/>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%; vertical-align: top;">
                    <table style="border: 1px solid #e6e6e6; width: 100%;border-collapse: collapse;">
                        <tr>
                            <td colspan="2" style="background-color: #f6f6f6; color:#747474; font-weight: bold; font-size: 14px; padding:10px; text-align: left;">Alamat Penagihan</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">Penerima </td>
                            <td style="font-weight: bold;vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">{{ $billing_address['name'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">Handphone</td>
                            <td style="font-weight: bold;vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">{{ $billing_address['phone'] }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">Alamat</td>
                            <td style="font-weight: normal;vertical-align: top; padding: 10px 0 10px 10px; border-top: 1px solid #e6e6e6;">
                                @if($billing_address)
                                    {{ $billing_address['address'] }}<br/>
                                    {{ $billing_address['province'] }}, {{ $billing_address['city'] }} {{ @$billing_address['postal_code'] }}<br/>
                                @endif</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    @if (isset($comment->text) && !empty($comment->text) && $comment->text != '-')
    <div class="destination" style="padding: 10px; margin-top: 20px; font-size: 12px; border: 1px solid #e6e6e6;">
        @if (str_word_count($comment->text) < 50)
            {{ $comment->text }}<br/><br/><br/>
        @else
            {{ $comment->text }}
        @endif
    </div>
    @endif
    <div class="dashed" style="border: 1px dashed #ccc; width: 100%; margin-top: 30px"></div>
    <div class="shipment" style="text-align: center; padding-top: 20px; font-style: italic; padding-bottom: 20px;font-size: 12px; color: #747474;">
        <span>“Terima kasih Anda telah berbelanja di Ralali.com”</span>
    </div>
    <div class="dashed" style="border: 1px dashed #ccc; width: 100%"></div>
    {{--<div class="icon" style="position: relative; left: 600px; width: 100%; top: 50px">
        <img width="100px" src="{{ cdn('assets/images/ralali-logo.png') }}">
    </div>--}}
    <div class="block" style="font-size: 12px; width: 100%; padding-bottom: 15px; padding-top: 10px; color: #747474;">
        <p>PT Raksasa Laju Lintang</p>
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
