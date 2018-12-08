@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="flex-center">
            <img src="/images/icon.jpg" alt="Social Genie" class="logo" />
        </div>
        <br />
        <hr style="width: 50%; margin: 0px auto;"/>
        <br />

        <div class="loading" style="display: none;"></div>
        <div class="links messageWrap" style="display: none">
            <span class="message">
                Referral users can use "{{ucwords(str_replace('_', ' ',env('APP_NAME')))}}" for free
            as long as the referer app is installed. Your fair play is appreciated.
                <br />
                Enjoy your free app !
            </span>
            <br />
            <span class="message">
                <span>Redirecting you in</span>&nbsp;<span id="counter">10</span>&nbsp;<span>seconds.</span>
            </span>
        </div>
        <script src="/js/appFrame.js"></script>
    </div>
@endsection
