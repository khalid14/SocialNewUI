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
            @if($status == 'declined')
                <span class="message">
                    <b>You canceled the charge !</b>
                </span>
                <br />
            @endif

            <span class="message">You need to accept billing to use {{env('APP_NAME_FORMATTED')}}.</span>
            <br /><br />
            <span>You can either ...</span>
            <br /><br /><br />
            <div class="fifty message verticalSep">
                Click below to go back and preform the
                <br />
                <a class="plain" href="{{$link}}" target="_parent"><button type="button" class="clean-gray slim">Billing Setup</button></a>
            </div>
            <div class="message fifty">
                Click below to go back to apps page and
                <br />
                <a class="plain" href="{{$apps}}" target="_parent"><button type="button" class="clean-gray slim">Uninstall App</button></a>
            </div>
        </div>
        <script src="/js/appFrame.js"></script>
    </div>
@endsection
