// ----- only continue if the injection is not loaded
if (window[lazyTargetGDPR.app] == undefined) {

    var jsHook = document.createElement('script');
    jsHook.type = 'text/javascript';
    jsHook.src = lazyTargetGDPR.base + '/pull/js/' + lazyTargetGDPR.shop + '/1';
    jsHook.async = true;
    document.querySelector('head').appendChild(jsHook);

    var cssHook = document.createElement('link');
    cssHook.rel = 'stylesheet';
    cssHook.type = 'text/css';
    cssHook.src = lazyTargetGDPR.base + '/pull/css/' + lazyTargetGDPR.shop;
    document.querySelector('head').appendChild(cssHook);
}