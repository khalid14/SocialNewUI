@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="flex-center">
            <img src="/images/icon.jpg" alt="Social Genie" class="logo" />
        </div>
        <br />
        <hr style="width: 50%; margin: 0px auto;"/>
        <br />

        <div class="links messageWrap">
            <span class="message">
                You uninstalled the referral app, so your free pass for "{{ucwords(str_replace('_', ' ',env('APP_NAME')))}}" has expired.
                <br />
                Please reinstall "{{ucwords(str_replace('_', ' ',env('APP_NAME')))}}".
                <br />
                Thanks for choosing us.
            </span>
        </div>
        <script src="/js/appFrame.js"></script>
    </div>
@endsection
