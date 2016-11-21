<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php
    $js_version = config('constants.JS_VERSION');
    $css_version = config('constants.CSS_VERSION');
    ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title or 'Sportsjun - Connecting Sports Community' }}</title>
    <!-- ********** CSS ********** -->
    <link href="{{ asset('/css/jquery-ui.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="/themes/sportsjun/css/style.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/stylesheet.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/themes/sportsjun/css/owl.carousel.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/custom.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/backslider.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/old.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/style_add.css" rel="stylesheet" type="text/css">

    @yield('head')


</head>

<body>
@include('home.partials.header')
<?php
if (isset($errors) && ($errors->count()))
    $error = $errors->keys()[0] . ': ' . $errors->first();
?>
@if(isset($error) && ($error))
    <script>
        alert('{{$error}}');
    </script>
@endif
<div class="clearfix">
    @yield('content')
</div>

@include('home.partials.footer')
<!-----------------popup---------------------->
@include('home.partials.modals')

<script src="/themes/sportsjun/js/jquery.min.js" type="text/javascript"></script>
<script src="{{ asset('/js/jquery-ui.min.gs') }}?v=<?php echo $js_version;?>" type="text/javascript"></script>
<script src="/themes/sportsjun/js/bootstrap.min.js"></script>
<script src="{{ asset('/home/js/sj.global.js') }}? v = <?php echo $js_version;?>"></script>
<script src="{{ asset('/home/js/sj.user.js') }}?v=<?php echo $js_version;?>"></script>
<!-- ********** ********** -->
<script src="/themes/sportsjun/js/owl.carousel.js"></script>
<script src="/themes/sportsjun/js/backslider.js"></script>
<script src="/themes/sportsjun/js/sportsjun.js"></script>

</body>
</html>
