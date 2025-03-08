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


  <style>
    .whatsapp-float {
      position: fixed;
      width: 60px;
      height: 60px;
      bottom: 40px;
      right: 40px;
      background-color: #25d366;
      color: #FFF;
      border-radius: 50px;
      text-align: center;
      box-shadow: 2px 2px 3px #999;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s;
      /* Animation for pulse effect */
      animation: pulse 2s infinite;
    }
  
    .whatsapp-float:hover {
      background-color: #128C7E;
      transform: scale(1.1);
      animation: none; /* Stop the animation on hover */
    }
  
    .whatsapp-float i {
      font-size: 30px;
      margin: 0;
      padding: 0;
      line-height: 1;
      /* Animation for the icon bounce */
      animation: bounce 1.5s ease infinite;
    }
  
    /* Pulse animation */
    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
      }
      70% {
        box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
      }
    }
  
    /* Bounce animation */
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-5px);
      }
      60% {
        transform: translateY(-3px);
      }
    }
  
    /* Initial entrance animation */
    .whatsapp-float {
      animation-name: pulse, entrance;
      animation-duration: 2s, 1s;
      animation-iteration-count: infinite, 1;
      animation-timing-function: ease, ease-out;
    }
  
    @keyframes entrance {
      0% {
        opacity: 0;
        transform: scale(0) rotate(360deg);
        right: 20px;
      }
      80% {
        opacity: 1;
        transform: scale(1.1) rotate(0deg);
        right: 40px;
      }
      100% {
        transform: scale(1) rotate(0deg);
        right: 40px;
      }
    }
  
    /* For mobile devices */
    @media screen and (max-width: 767px) {
      .whatsapp-float {
        width: 50px;
        height: 50px;
        bottom: 20px;
        right: 20px;
      }
      
      .whatsapp-float i {
        font-size: 24px;
      }
    }
  </style>
  
  <!-- Add Font Awesome for the WhatsApp icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
