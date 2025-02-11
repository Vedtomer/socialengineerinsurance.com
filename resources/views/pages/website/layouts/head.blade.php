<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8F6MCQG2HV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-8F6MCQG2HV');
    </script>
    {!! SEO::generate() !!}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('asset/website/images/favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('asset/website/images/favicon/apple-touch-icon.png') }}">
    <link href="{{ asset('asset/website/css/web.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/website/css/style.css') }}" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n
                .className += t + "touch")
        }(window, document);
    </script>
</head>
