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
                "{{env('APP_NAME_FORMATTED')}}" is Affiliate friendly, therefore you can test out the complete app for free
                as long as you want on this store. Your fair play is appreciated.
            </span>
            <br />

            <span class="message">
                <span>Redirecting you in</span>&nbsp;<span id="counter">10</span>&nbsp;<span>seconds.</span>
            </span>

        </div>
        <script src="/js/appFrame.js"></script>
    </div>
@endsection
