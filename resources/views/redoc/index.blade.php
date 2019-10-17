<!DOCTYPE html>
<html>
<head>
    <title>RALALI API DOCUMENTATION</title>
    <!-- needed for adaptive design -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--
    ReDoc doesn't change outer page styles
    -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .sc-Rmtcm {
            background-image: linear-gradient(0deg, #F4761E 0%, #FDB014 100%);
            height: 100px;
            text-align: left !important;
        }
        .sc-Rmtcm img {
            width: 160px !important;
            margin: 10px 0 0 50px;
        }
        .sc-eXEjpC {
            margin: 10px 10px;
            background-color: white;
            border: 1px solid gray;
            border-radius: 5px;
        }
        .sc-ibxdXY {
            border: 0 !important;
        }
    </style>
</head>
<body>
<redoc spec-url='<?php echo $documentationFile; ?>'></redoc>
<script src="https://cdn.jsdelivr.net/npm/redoc@2.0.0-alpha.17/bundles/redoc.standalone.js"> </script>
</body>
</html>