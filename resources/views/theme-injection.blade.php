@extends('layouts.app', ['skipFoot' => true])

@section('content')
    <div class="container">
        <div class="toggle">
            <div class="toggle-title active">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> How we handle theme modification in our app ?</span>
                </h3>
            </div>
            <div class="toggle-inner" style="display: block">
                <blockquote class="blockquote">
                    <p><b>"{{env('APP_NAME_FORMATTED')}}"</b> takes care of all the theme modifications for you, you dont have to worry
                        about manually editing your theme and inject / remove code to make our app work.
                    </p>
                    <footer class="blockquote-footer"><cite title="Source Title">The {{env('APP_NAME_FORMATTED')}} Team.</cite></footer>
                </blockquote>

                <mark>
                    To facilitate that, we perform
                    an <b>"integrity check"</b> each time you launch our app, to see if the theme code is intact, and if we find any issues
                    with that, we fix it automatically. We would also like to suggest that if you (ever) like to uninstall "{{env('APP_NAME_FORMATTED')}}"
                    you should do that by the <b>"Clean Uninstall"</b> button provided under actions menu, this way we can effectively take care of all the
                    theme code we injected, and clean your theme from unwanted code.
                </mark>
                <br />
                <br />
                <p>But if ever things get out of hand or you like to manually modify / inject / remove our code from the Shopify theme,
                    here is a guide which will allow you to exactly do that.
                </p>
                <p>There are two parts of the theme injection for "{{env('APP_NAME_FORMATTED')}}"</p>
                <ol>
                    <li>The place holder injection in <b>theme.liquid</b></li>
                    <li>The <b>"{{env('APP_NAME')}}.liquid"</b> snippet in "snippets" section</li>
                </ol>
                <br />
                <p>Lets get to the correct location first, navigate to themes page as shown below.<br />
                <p class="content"><img src="/images/steps/1.png" width="60%" align="center"></p>
                    <br />
                    @if(Session::has('shop'))
                        Or click this link <a target="_blank" href="https://{{Session::get('shop')}}/admin/themes">https://{{Session::get('shop')}}/admin/themes</a>
                        to open the themes page.
                    @endif
                </p>
                <p>Now Click the "Customize" Button, as shown bellow</p>
                <p class="content"><img src="/images/steps/2.png" width="80%" align="center"></p>
                <p>This will bring you to the theme customization section of Shopify, now click the "Theme Actions" button on bottom left
                    of the screen, and select the middle option "Edit Code".
                </p>
                <p class="content"><img src="/images/steps/3.png" width="80%" align="center"></p>
                <p>On the next screen click on the <b>theme.liquid</b> link</p>
                <p class="content"><img src="/images/steps/4.png" width="80%" align="center"></p>
                <p style="float: left;">We are looking for </p>
                    <pre class="inlinePre">{% include "{{env('APP_NAME')}}" %}</pre></b>
                <p>code ...</p>
                <p class="content"><img src="/images/steps/5.png" width="80%" align="center"></p>
                <p style="float: left">You can remove that code or add it, if not found already somewhere after the</p>
                    <pre class="inlinePre">&lt;/title&gt;</pre></b>
                <p>tag ...</p>
                <p>The second bit is to create / remove a snippet in snippets section by the name <b>{{env('APP_NAME')}}.liquid</b></p>
                <p class="content"><img src="/images/steps/6.png" width="80%" align="center"></p>
                <p>If you want to remove it, just delete it from the <b>"Delete"</b> button on right side of screen.</p>
                <p>If you want to create that, just click on the <b>"Add a new snippet"</b> button. and on the next screen add these to the snippet and hit "<b>Save</b>".</p>
                <pre class="inlinePre" style="float: none; font-size: 12px; padding: 0px;">
&lt;!-- App Hook start--&gt;
&lt;link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/pull/css/your-store.myshopify.com"&gt;
&lt;script type="text/javascript" src="{{env('APP_URL')}}/pull/js/your-store.myshopify.com"&gt;&lt;/script&gt;
&lt;!-- App Hook end--&gt;
                </pre>
                <p>Thats it, thats all it is to modify theme for the app.</p>
            </div>
        </div><!-- END OF TOGGLE -->

    </div>

    <style>
        html {
            overflow: hidden;
        }
        body {
            overflow-x: hidden;
            overflow-y: scroll;
            box-sizing: content-box;
        }
        .flex-center {
            display: block;
        }
    </style>
@endsection
