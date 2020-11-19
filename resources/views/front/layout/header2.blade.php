<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Jeettii</title>
    <link rel="shortcut icon" type="image/x-icon" href="{!! asset('front/favicon.ico') !!}">
    <!-- Bootstrap -->
    <link href="{!! asset('front/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css">
    <!-- Owl Carousel -->
    <link href="{!! asset('front/css/owl.carousel.min.css') !!}" rel="stylesheet" type="text/css">
    <!-- Fancybox -->
    <link rel="stylesheet" href="{!! asset('front/css/jquery.fancybox.min.css') !!}" type="text/css" />
    <!-- Theme Style -->
    <link href="{!! asset('front/css/style.css') !!}" rel="stylesheet" type="text/css">
    <!-- Custom Style -->
    <link href="{!! asset('front/css/custom.css') !!}" rel="stylesheet" type="text/css">

    <!-- Css Files Start -->
    <link href="{!! asset('front/css3/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('front/css3/fontawesome-all.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('front/css3/owl.carousel.css') !!}" rel="stylesheet">

    <link rel="stylesheet" href="{!! asset('asset/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') !!}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{!! asset('asset/select2/dist/css/select2.min.css') !!}">

    <link rel="stylesheet" href="{!! asset('asset/plugins/timepicker/bootstrap-timepicker.min.css') !!}" />
    <link rel="stylesheet" href="{!! asset('front/custom/loading.css') !!}" />
    <link rel="stylesheet" href="{!! asset('front/custom/custom.css') !!}" />

    <!-- Css Files End -->

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        function flightt() {
            // Get the checkbox
            var checkBox = document.getElementById("flight");
            // Get the output text
            var text = document.getElementById("flightno");
            var fli0 = document.getElementById("fli");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>

    <script>
        function meetingg() {
            // Get the checkbox
            var checkBox = document.getElementById("meeting");
            // Get the output text
            var text = document.getElementById("meeeting");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>

    <script>
        function promoo() {
            // Get the checkbox
            var checkBox = document.getElementById("promo");
            // Get the output text
            var text = document.getElementById("promocode");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>
    <script>
        function returnn() {
            // Get the checkbox
            var checkBox = document.getElementById("return2");
            // Get the output text
            var text = document.getElementById("reeturn");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>

    <script>
        function flightt2() {
            // Get the checkbox
            var checkBox = document.getElementById("flight2");
            // Get the output text
            var text = document.getElementById("flightno2");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>

    <script>
        function meetingg2() {
            // Get the checkbox
            var checkBox = document.getElementById("meeting2");
            // Get the output text
            var text = document.getElementById("meeeting2");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>

    <script>
        function promoo2() {
            // Get the checkbox
            var checkBox = document.getElementById("promo2");
            // Get the output text
            var text = document.getElementById("promocode2");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>
</head>
<body>
<div style="display: none;" class="loading"></div>
<!--Wrapper Content Start-->
<!--Header area start here-->
<header>

    @include('front.layout.menu')

</header>
<!-- Header area end here -->