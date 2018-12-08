function inIframe () {
    try { return window.self !== window.top;}
    catch (e) {return true; }
}

if (inIframe()) {
    var links = document.querySelector('.links');
    links.style.display = "block";
} else {
    var loading = document.querySelector('.loading');
    loading.style.display = "block";
}

if (appCredentials.shopOrigin != 'https://') {
    ShopifyApp.init(appCredentials);
}

var redirect = setInterval(function() {
    var div = document.querySelector("#counter");
    var count = div.textContent * 1 - 1;

    if (count <= 0) {
        window.location.replace(appCredentials.landingPage);
        clearInterval(redirect);
    } else {
        div.textContent = count;
    }
}, 1000);