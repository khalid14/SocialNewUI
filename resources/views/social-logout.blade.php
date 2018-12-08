!<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
</head>
<body>
<script>
    var opener = window.opener;
    if (opener) {
        var oDom = opener.document;
        var elem = oDom.getElementById('logout-{{$platform}}');
        if (elem) {
           // set logout link
            elem.innerHTML = '';
            // set login link
            elem.previousElementSibling.value =  htmlDecode('{{$loginLink}}');;
        }
        function htmlDecode(input){
            var e = document.createElement('div');
            e.innerHTML = input;
            // handle case of empty input
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        }
    }
    window.close();
</script>

</body>
</html>