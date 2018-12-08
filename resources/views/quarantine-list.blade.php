@extends('layouts.app', ['skipFoot' => true])

@section('content')
    <div class="container">
        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png">Supported Platforms ...</span>
                </h3>
            </div>
            <div class="toggle-inner" style="display: block">
                <p>Currently we support following platforms, and can effectively block / release userdata for these.
                <ul>
                    <li>Facebook Pixels</li>
                    <li>Google Analytics</li>
                    <li>Google Tags Manager</li>
                    <li>HotJar</li>
                    <li>Extreme DM</li>
                </ul>
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
