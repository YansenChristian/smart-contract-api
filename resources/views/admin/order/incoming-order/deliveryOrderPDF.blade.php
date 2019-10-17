<!DOCTYPE html>
<html>
<head>
    <title>Print Delivery Order</title>


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
        html {margin: 0px}
        body {}
    </style>
</head>

<body style="font-family: 'Noto Sans', sans-serif;">
<div style="background-color: #ff5700; width: 843px; height: 100px; background-size: cover; width: 100%; margin-bottom: 25px;">
    <img src="{{ cdn('assets/img/microsite/logo/'.$do->logo) }}" style="padding: 20px;border-radius: 25%; width:60px; height:60px;">
    <span class="move" style="font-size: 24px; color: white; position: absolute; left: 30px;line-height: 75px;margin-left: 75px;"><strong>{{ @$do->name_shop }}</strong></span>
    <span class="order" style="letter-spacing: 1px; position: absolute;right: 40px;font-size: 16px;font-weight: bold;color: white;line-height: 75px;">DELIVERY ORDER</span>
</div>
<div class="destination" style="width: 100%; margin-top: 20px;font-size: 13px; color: #444">
    <table style="width: 100%;border-spacing: 0px;">
        <tr>
            <td style="padding: 0 20px;width: 50%; vertical-align: top;">
                <table style="border-spacing: 0px;">

                    <tr>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px">Nomor-Delivery Order</td>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px">:</td>
                        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;">{{ $do->delivery_order_serial }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px">Nomor Invoice</td>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px">:</td>
                        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;">{{ @$do->invoice_serial_id }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px">Tanggal</td>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px">:</td>
                        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;">{{ date('d-M-Y H:i:s', strtotime($do->created_at)) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px">Lokasi Penjual</td>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px">:</td>
                        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;">{{ @$do->province_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 80px; padding-left: 10px">Metode Pembayaran</td>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px; padding-left: 20px">:</td>
                        <td style="padding-bottom: 5px; font-weight: bold;vertical-align: top;">{{ @$do->payment_method }}</td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top;">
                <table style="border-spacing: 0px;">

                    <tr>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 80px;">Kepada</td>
                        <td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px">:</td>
                        <td style="padding-bottom: 5px; font-weight: normal;vertical-align: top;">
                            <strong>{{ $do->name }}</strong><br/>
                        </td>
                    </tr>
                    {{--<tr>--}}
                        {{--<td style="padding-bottom: 5px; vertical-align: top;width: 80px;">Alamat</td>--}}
                        {{--<td style="padding-bottom: 5px; vertical-align: top;width: 5px; padding-right: 20px">:</td>--}}
                        {{--<td style="padding-bottom: 5px; font-weight: normal;vertical-align: top;">--}}
                            {{--*/ $shippingAdd = json_decode($do->shipping_address) /*--}}

                            {{--{{ $shippingAdd->address }}<br/>--}}
                            {{--{{ $shippingAdd->city_name }}, {{ $shippingAdd->subdistrict_name }} - {{ $shippingAdd->province_name }}<br/>--}}
                            {{--{{ $shippingAdd->postal_code }}--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td style="vertical-align: top;width: 80px;">Telepon</td>--}}
                        {{--<td style="vertical-align: top;width: 5px;">:</td>--}}
                        {{--<td style="font-weight: normal;vertical-align: top;">{{ $shippingAdd->phone }}</td>--}}
                    {{--</tr>--}}
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="list-produk" style="margin: 0 30px">
    <table class="local" style="width: 100%; font-size: 100%; margin-top: 30px; border: 1px solid #e6e6e6; border-collapse: collapse; color: #444">

        <tr class="order-list" style="background-color: #f6f6f6;">
            <th style="font-weight: bold; color: #b6b6b6; text-align: left; padding: 15px 0px 15px 15px; font-size: 12px; border-bottom: 1px solid #e6e6e6"><strong>NAMA BARANG</strong></th>
            <th style="font-weight: bold; color: #b6b6b6; text-align: left; padding: 15px 0px 15px 15px; font-size: 12px; border-bottom: 1px solid #e6e6e6"><strong>JUMLAH</strong></th>
        </tr>
        {{--*/ $countItem = 0 /*--}}
        {{--@foreach($detail as $listRow)--}}
            {{--<tr class="order-list" style="border-bottom: 1px dashed #c8c8c8">--}}
                {{--<th style="text-align: left; height: 60px; padding-left: 25px; font-size: 12px; font-weight: normal;">{{ $listRow->item_name }}</th>--}}
                {{--<th style="text-align: left; height: 60px; padding-left: 25px; font-size: 12px; font-weight: normal;">{{ $listRow->product_quantity }}</th>--}}
                {{--*/ $countItem = $countItem + $listRow->product_quantity  /*--}}
            {{--</tr>--}}
        {{--@endforeach--}}
        {{--<tr class="order-list" style="border: 1px groove #ccc;background-color: #f6f6f6;">--}}
            {{--<th style="color: #42b449; font-size: 14px; text-align: left; height: 50px; font-weight: normal; text-align:right;"><strong>TOTAL BARANG</strong></th>--}}
            {{--<th style="color: #42b449; font-size: 14px; text-align: left; height: 50px; padding-left: 25px;font-weight: normal"><strong>{{ $countItem }}</strong></th>--}}
        {{--</tr>--}}
    </table>
    @if (isset($do->comment_text) && $do->comment_text != '-' && !empty($do->comment_text))
    <table class="local" style="min-height: 100px; width: 100%; font-size: 12px; margin-top: -10px; margin-bottom: 20px; border: 1px solid #e6e6e6; border-collapse: collapse; color: #444">
        <tr class="order-list" style="">
        @if (str_word_count($do->comment_text) < 50)
            <td style="padding: 10px;">{{ $do->comment_text }}<br/><br/><br/></td>
        @else
            <td style="padding: 10px;">{{ $do->comment_text }}</td>
        @endif
        </tr>
    </table>
    @endif
    <div class="destination" style="width: 100%; margin-top: 30px;">
        <table class="numbre" style="border-spacing: 0px;padding-left: 15px">
            <tr>
                <td style="font-size: 12px; font-style: italic;border: 1px solid #ccc; color: #444;width: 170px">
                    <p style="padding-bottom: 70px;text-align: center">Prepared By</p>
                    <p style="border-top: 1px solid #ccc; height: 1px;margin-left: 10px;margin-right: 10px"></p>
                </td>
                <td width="5"></td>
                <td style="font-size: 12px; font-style: italic;border: 1px solid #ccc; color: #444;width: 170px">
                    <p style="padding-bottom: 70px;text-align: center">Approved By</p>
                    <p style="border-top: 1px solid #ccc; height: 1px;margin-left: 10px;margin-right: 10px"></p></td>
                <td width="5"></td>
                <td style="font-size: 12px; font-style: italic;border: 1px solid #ccc; color: #444;width: 170px">
                    <p style="padding-bottom: 70px;text-align: center">Shipped By</p>
                    <p style="border-top: 1px solid #ccc; height: 1px;margin-left: 10px;margin-right: 10px"></p></td></td>
                <td width="5"></td>
                <td style="font-size: 12px; font-style: italic;border: 1px solid #ccc; color: #444;width: 170px">
                    <p style="padding-bottom: 70px;text-align: center">Received By</p>
                    <p style="border-top: 1px solid #ccc; height: 1px;margin-left: 10px;margin-right: 10px"></p></td></td>
            </tr>
        </table>
    </div>
        
    <div class="shipment" style="text-align: center; padding-top: 20px; font-style: italic; padding-bottom: 20px; font-size: 12px; color: #747474;">
        <span style="padding-right: 20px;">“Terima kasih Anda telah berbelanja di Ralali.com”</span>
    </div>
    <div class="dashed" style="border: 1px dashed #ccc; width: 100%"></div>
    <footer style="position: absolute;bottom: 60px">
    {{-- <div class="icon" style="position: relative; left: 600px; width: 100%; top: 50px">
        <img width="100px" src="{{ cdn('assets/images/ralali-logo.png') }}">
    </div> --}}
    <div class="block" style="font-size: 12px; width: 100%; bottom: 10px; color: #747474">
        <p>PT Raksasa Laju Lintang</p>
        <p>Ruko Prominence D38 No. 51-53 Alam Sutera Tangerang, Banten 15143</p>
        <p>Telp: 021-30052777</p>
    </div>
    </footer>
    <div class="line-color">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
</body>
</html>
