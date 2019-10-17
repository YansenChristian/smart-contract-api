<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Ralali</title>
    <meta name="generator" content="Bootply" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    </head>
    <body>
    <style>
        body{
        width:800px;
        margin:0 auto;
        }
        .ask{
        width:100%;
        background-color:#e6e6e6;
        text-align:center;
        padding:40px 0 20px 0;
        font-family:Arial,Helvetica;
        font-size:14px;
             color:#676767;
        }
        #logo{
        text-align:center;
        padding:20px 0;
        border-bottom:1px solid #cccccc;
        }
        #content{
        padding:20px 20px;
        }
        .open-words{
        text-align:center;
        font-family:Arial, Helvetica;
        margin-bottom:20px;
            color:#676767;
        }
        .black{
        color:#000000;
        }
        .order-number{
        font-size:24px;
        font-weight:bold;
        }
        .bordered-box{
        
        padding: 20px;
        font-family:Arial,Helvetica;
        border:1px solid #cccccc;
        font-size:14px;
        width:100%;
            color:#676767;
        
        }
        .col-3{
        width:25%;
        }
        .col-1{
        width:8.33%;
        }
        .col-8{
        width:66.66%;
        }
        .col-6{
        width:50%;
            float:left;
            text-align:center;
        }
        .status{
        color:#6BB848;
            font-weight:bold;
        }
        .order-table{
        width:100%;
        text-align:left;
        font-family:Arial,Helvetica;
        font-size:14px;
        margin-top:20px;
        }
        .order-table td
        {
        padding:20px;    
        text-align:left;
        color:#666666;
        border-bottom:1px solid #cccccc;
        }
        .order-table th
        {
        padding:15px 20px 15px 20px;    
        text-align:left;
        background-color:#676767;
        color:#ffffff;
        font-weight:normal;
        }
        .checkout-label{
        font-weight:bold;
        }
        .total{
        color:#000000;
        font-weight:bold;
        }
        .checkout > td {
        border-bottom:0;
        }
        .suggest{
        background:url(../assets/img/email/strip.png) no-repeat;
        height:100px;
        width:100%;
        font-family:Arial,Helvetica;
        font-size:14px;
        }
        .suggest > *{
        padding:40px 0;
        }
        .submit-form{
        padding:30px 0;
        }
        .submit-form input{
        height:40px;
        width:210px;
        padding-left:15px;
        border-left-color:#ffffff;
        border:1px solid #cccccc;    
        }
        .submit-form button{
        height:44px;
        padding:10px 0;
        width:150px;
        background-color:#ed1c24;
        color:#ffffff;
        font-family:Arial,Helvetica;
        border:0;
        font-weight:bold;
        border-bottom:5px solid #bc151b;
        border-top:4px solid #ed1c24;
        }
        .discount{
        margin-top:40px;
        
        }
        .products{
        font-family:Arial,Helvetica;
        padding:0 20px;
        margin-top:40px;
        
        }
        .products .col-3{
        text-align:center;
        padding-top:15px; 
        }
       
        .product-detail .product-name{
        font-size:14px;
        font-weight:bold;
            margin-bottom:10px;
        }
        .product-detail .priceOld{
        font-size:12px;
        text-decoration:line-through;
        color:#a6a8ab;
        }
        .product-detail .priceNew{
        font-size:20px;
        font-weight:bold;
        color:#ba141a;
        }
        .contact{
        font-family:Arial,Helvetica;
        width:100%;
        font-size:14px;
        padding:40px 20px 40px 20px;
        border-top:1px solid #cccccc;
        margin-top:40px;
        color:#676767;
        }
        .contact .col-6{
        text-align:left;
        margin-bottom:40px;
        }
        
        .mail{
        margin-bottom:15px;
        }
        .mail > a{
        text-decoration:none;
        color:#000000;
        
        }
        .socmed{
        color:#000000;
        padding-left:60px;
        
        }
        .socmedIcon a{
        text-decoration:none;
        display:inline-block;
        float:left;
            margin:15px 20px 0 0;
        }
        .col-5{
        width:41.66%;
        float:left;
        }
        
        .status a{
            color: #6BB848
        }
    </style>    
        <div id="header">
            <div class="ask">
                Can't view this email ? <a href="#" style="text-decoration: none"><b class="black">Open on your browser</b></a>
            </div>
            <div id="logo">
                <a href="{{asset('')}}" target="_blank"><img src="{!! cdn('assets/img/logobaru.png') !!}" alt="Logo Ralali.com"></a>
            </div>
                
        </div>
        <div id="content">
            <div class="open-words">
                <p>Dear <span class="black">{{$name}},</span></p><b>Anda</b> telah melakukan pemesanan dengan kode pemesanan <b>{{$order_serial}}</b><br>
                Mohon selesaikan proses pembayaran Anda sebelum tanggal jatuh tempo. <br><br>
                Silahkan klik link dibawah untuk melihat rincian pemesanan<br>
                <a href="{{action('Customer\CustomerController@getLoginByOrderSerial',str_replace('/', '---', $order_serial))}}" style="color: #6BB848">Pesanan Saya</a>             
            </div>
        </div>
        <div id="footer">
            <div class="contact">
                <div class="col-6 ">
                    <div class="mail">Send to : <a href="#"><b>sales@ralali.com</b></a></div>
                    <div class="address">Lindeteves Trade Center Lt.UG Blok A23 No.6</br> Jl. Hayam Wuruk 127,Jakarta Barat 11180 - Indonesia  </br>+ 62 21 3331 6506 Jam Kerja : 11:00 - 16:00 WIB</div> 
                </div>
                <div class="col-5 socmed">
                    Connect with us:
                    <div class="socmedIcon">
                       <a href="https://www.facebook.com/ralalicom">
                        <img src="{!! cdn('assets/img/email/facebook.png')!!}" alt="Facebook">
                        </a>
                        <a href="https://twitter.com/ralalicom">
                        <img src="{!! cdn('assets/img/email/twitter.png')!!}" alt="Twitter">
                        </a>
                        <a href="https://plus.google.com/+RalaliCom/about">
                        <img src="{!! cdn('assets/img/email/gplus.png')!!}" alt="Google +">
                        </a>
                        <a href="https://www.linkedin.com/company/ralali-com">
                        <img src="{!! cdn('assets/img/email/in.png')!!}" alt="LinkedIn">
                        </a>
                    </div>
                </div>
                     

            </div>

        
        </div>
    </body>
</html>