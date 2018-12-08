@extends('layouts.app', ['skipFoot' => true])

@section('content')
    <div class="container">
        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png">Want our assistance with something ?</span>
                </h3>
            </div>
            <div class="toggle-inner" style="display: block">
                <p>If you need assistance with any aspect of our app or your shop, We recommend that you give a try to
                    the intercom customer support at the bottom right of the screen, and our customer support ninjas will
                    be with you in no time :)
                </p>
                <br />
                <p>But still if you want to reachout to us or want to discuss something about our app, just drop us a word at ...</p>
                <br />
                <p class="message center">
                <a href="mailto:support@redretarget.com">support@redretarget.com</a>
                </p>
            </div>
        </div>
    </div>
    <style>
        html {
            overflow: hidden;
        }
        body {
            overflow: hidden;
            box-sizing: content-box;
        }
        .center {
            text-align: center;
        }
    </style>
@endsection
