<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>1810微商城-@yield('title')</title>
    <link rel="shortcut icon" href="{{asset('index/images/favicon.ico')}}" />

    <!-- Bootstrap -->
    <link href="{{asset('index/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('index/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('index/css/response.css')}}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{asset('layui/layui.js')}}"></script>


    <script src="{{asset('index/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('index/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('index/js/style.js')}}"></script>
    <!--焦点轮换-->
    <script src="{{asset('index/js/jquery.excoloSlider.js')}}"></script>
    <script>
        $(function () {
            $("#sliderA").excoloSlider();
        });
    </script>
</head>
<body>
<div class="maincont">

@yield('content')


</body>
</html>