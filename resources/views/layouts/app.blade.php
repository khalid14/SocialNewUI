<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME_FORMATTED')}}</title>

    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png" />
    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="stylesheet" type="text/css" href="css/slim.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    <script type="text/javascript">
        var appCredentials = {
            apiKey: '{{env('SHOPIFY_API_KEY')}}',
            shopOrigin: 'https://{{session('shop')}}',
            landingPage: '{{route('landing-page')}}'
        };
    </script>
    @yield('css')
</head>
<body>
<div class="flex-center position-ref full-height">
    @yield('content')
</div>
@if(!isset($skipFoot))
<div class="flex-center position-ref footer">
    <a href="https://1dea.co" target="_blank">
        <span class="copyright">
            ©&nbsp;<b style="color: #000; font-weight: bold;">1DEA</b>&nbsp<span style="color: #000;">Corp</span>&nbsp;<script>document.write(''+(new Date()).getFullYear()+'');</script>
        </span>
    </a>
</div>
@endif
<script>
    function yooo() {
        var shop = document.querySelector('.shopName').value;
        shop = shop.replace('http:', 'https:');
        shop = shop.startsWith('https') ? shop : 'https://'+shop;
        shop = shop.endsWith('/') ? shop : shop+'/';

        window.location.href = shop + 'admin/api/auth?api_key={{env('SHOPIFY_API_KEY')}}';
    }
</script>
</body>
</html>
