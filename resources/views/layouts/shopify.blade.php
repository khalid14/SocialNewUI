<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME_FORMATTED')}}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="/images/app-favicon.png" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- <script src="https://cdn.shopify.com/s/assets/external/app.js"></script> -->

    <script>
        {{--ShopifyApp.init({--}}
            {{--apiKey: '{{$api_key}}',--}}
             {{--shopOrigin: 'https://{{$shop}}'--}}
        {{--});--}}

        window.appBase = '{{env('APP_URL')}}';
        window.appName = '{{env('APP_NAME_FORMATTED')}}';
    </script>
</head>

<body>
    @yield('content')
    @include('includes')
	<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
