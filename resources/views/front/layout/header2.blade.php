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

    <style>
        @font-face{font-family:'Glyphicons Halflings';src:url('https://netdna.bootstrapcdn.com/bootstrap/3.0.0/fonts/glyphicons-halflings-regular.eot');src:url('https://netdna.bootstrapcdn.com/bootstrap/3.0.0/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),url('https://netdna.bootstrapcdn.com/bootstrap/3.0.0/fonts/glyphicons-halflings-regular.woff') format('woff'),url('https://netdna.bootstrapcdn.com/bootstrap/3.0.0/fonts/glyphicons-halflings-regular.ttf') format('truetype'),url('https://netdna.bootstrapcdn.com/bootstrap/3.0.0/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');}.glyphicon{position:relative;top:1px;display:inline-block;font-family:'Glyphicons Halflings';font-style:normal;font-weight:normal;line-height:1;-webkit-font-smoothing:antialiased;}
        .glyphicon-asterisk:before{content:"\2a";}
        .glyphicon-plus:before{content:"\2b";}
        .glyphicon-euro:before{content:"\20ac";}
        .glyphicon-minus:before{content:"\2212";}
        .glyphicon-cloud:before{content:"\2601";}
        .glyphicon-envelope:before{content:"\2709";}
        .glyphicon-pencil:before{content:"\270f";}
        .glyphicon-glass:before{content:"\e001";}
        .glyphicon-music:before{content:"\e002";}
        .glyphicon-search:before{content:"\e003";}
        .glyphicon-heart:before{content:"\e005";}
        .glyphicon-star:before{content:"\e006";}
        .glyphicon-star-empty:before{content:"\e007";}
        .glyphicon-user:before{content:"\e008";}
        .glyphicon-film:before{content:"\e009";}
        .glyphicon-th-large:before{content:"\e010";}
        .glyphicon-th:before{content:"\e011";}
        .glyphicon-th-list:before{content:"\e012";}
        .glyphicon-ok:before{content:"\e013";}
        .glyphicon-remove:before{content:"\e014";}
        .glyphicon-zoom-in:before{content:"\e015";}
        .glyphicon-zoom-out:before{content:"\e016";}
        .glyphicon-off:before{content:"\e017";}
        .glyphicon-signal:before{content:"\e018";}
        .glyphicon-cog:before{content:"\e019";}
        .glyphicon-trash:before{content:"\e020";}
        .glyphicon-home:before{content:"\e021";}
        .glyphicon-file:before{content:"\e022";}
        .glyphicon-time:before{content:"\e023";}
        .glyphicon-road:before{content:"\e024";}
        .glyphicon-download-alt:before{content:"\e025";}
        .glyphicon-download:before{content:"\e026";}
        .glyphicon-upload:before{content:"\e027";}
        .glyphicon-inbox:before{content:"\e028";}
        .glyphicon-play-circle:before{content:"\e029";}
        .glyphicon-repeat:before{content:"\e030";}
        .glyphicon-refresh:before{content:"\e031";}
        .glyphicon-list-alt:before{content:"\e032";}
        .glyphicon-flag:before{content:"\e034";}
        .glyphicon-headphones:before{content:"\e035";}
        .glyphicon-volume-off:before{content:"\e036";}
        .glyphicon-volume-down:before{content:"\e037";}
        .glyphicon-volume-up:before{content:"\e038";}
        .glyphicon-qrcode:before{content:"\e039";}
        .glyphicon-barcode:before{content:"\e040";}
        .glyphicon-tag:before{content:"\e041";}
        .glyphicon-tags:before{content:"\e042";}
        .glyphicon-book:before{content:"\e043";}
        .glyphicon-print:before{content:"\e045";}
        .glyphicon-font:before{content:"\e047";}
        .glyphicon-bold:before{content:"\e048";}
        .glyphicon-italic:before{content:"\e049";}
        .glyphicon-text-height:before{content:"\e050";}
        .glyphicon-text-width:before{content:"\e051";}
        .glyphicon-align-left:before{content:"\e052";}
        .glyphicon-align-center:before{content:"\e053";}
        .glyphicon-align-right:before{content:"\e054";}
        .glyphicon-align-justify:before{content:"\e055";}
        .glyphicon-list:before{content:"\e056";}
        .glyphicon-indent-left:before{content:"\e057";}
        .glyphicon-indent-right:before{content:"\e058";}
        .glyphicon-facetime-video:before{content:"\e059";}
        .glyphicon-picture:before{content:"\e060";}
        .glyphicon-map-marker:before{content:"\e062";}
        .glyphicon-adjust:before{content:"\e063";}
        .glyphicon-tint:before{content:"\e064";}
        .glyphicon-edit:before{content:"\e065";}
        .glyphicon-share:before{content:"\e066";}
        .glyphicon-check:before{content:"\e067";}
        .glyphicon-move:before{content:"\e068";}
        .glyphicon-step-backward:before{content:"\e069";}
        .glyphicon-fast-backward:before{content:"\e070";}
        .glyphicon-backward:before{content:"\e071";}
        .glyphicon-play:before{content:"\e072";}
        .glyphicon-pause:before{content:"\e073";}
        .glyphicon-stop:before{content:"\e074";}
        .glyphicon-forward:before{content:"\e075";}
        .glyphicon-fast-forward:before{content:"\e076";}
        .glyphicon-step-forward:before{content:"\e077";}
        .glyphicon-eject:before{content:"\e078";}
        .glyphicon-chevron-left:before{content:"\e079";}
        .glyphicon-chevron-right:before{content:"\e080";}
        .glyphicon-plus-sign:before{content:"\e081";}
        .glyphicon-minus-sign:before{content:"\e082";}
        .glyphicon-remove-sign:before{content:"\e083";}
        .glyphicon-ok-sign:before{content:"\e084";}
        .glyphicon-question-sign:before{content:"\e085";}
        .glyphicon-info-sign:before{content:"\e086";}
        .glyphicon-screenshot:before{content:"\e087";}
        .glyphicon-remove-circle:before{content:"\e088";}
        .glyphicon-ok-circle:before{content:"\e089";}
        .glyphicon-ban-circle:before{content:"\e090";}
        .glyphicon-arrow-left:before{content:"\e091";}
        .glyphicon-arrow-right:before{content:"\e092";}
        .glyphicon-arrow-up:before{content:"\e093";}
        .glyphicon-arrow-down:before{content:"\e094";}
        .glyphicon-share-alt:before{content:"\e095";}
        .glyphicon-resize-full:before{content:"\e096";}
        .glyphicon-resize-small:before{content:"\e097";}
        .glyphicon-exclamation-sign:before{content:"\e101";}
        .glyphicon-gift:before{content:"\e102";}
        .glyphicon-leaf:before{content:"\e103";}
        .glyphicon-eye-open:before{content:"\e105";}
        .glyphicon-eye-close:before{content:"\e106";}
        .glyphicon-warning-sign:before{content:"\e107";}
        .glyphicon-plane:before{content:"\e108";}
        .glyphicon-random:before{content:"\e110";}
        .glyphicon-comment:before{content:"\e111";}
        .glyphicon-magnet:before{content:"\e112";}
        .glyphicon-chevron-up:before{content:"\e113";}
        .glyphicon-chevron-down:before{content:"\e114";}
        .glyphicon-retweet:before{content:"\e115";}
        .glyphicon-shopping-cart:before{content:"\e116";}
        .glyphicon-folder-close:before{content:"\e117";}
        .glyphicon-folder-open:before{content:"\e118";}
        .glyphicon-resize-vertical:before{content:"\e119";}
        .glyphicon-resize-horizontal:before{content:"\e120";}
        .glyphicon-hdd:before{content:"\e121";}
        .glyphicon-bullhorn:before{content:"\e122";}
        .glyphicon-certificate:before{content:"\e124";}
        .glyphicon-thumbs-up:before{content:"\e125";}
        .glyphicon-thumbs-down:before{content:"\e126";}
        .glyphicon-hand-right:before{content:"\e127";}
        .glyphicon-hand-left:before{content:"\e128";}
        .glyphicon-hand-up:before{content:"\e129";}
        .glyphicon-hand-down:before{content:"\e130";}
        .glyphicon-circle-arrow-right:before{content:"\e131";}
        .glyphicon-circle-arrow-left:before{content:"\e132";}
        .glyphicon-circle-arrow-up:before{content:"\e133";}
        .glyphicon-circle-arrow-down:before{content:"\e134";}
        .glyphicon-globe:before{content:"\e135";}
        .glyphicon-tasks:before{content:"\e137";}
        .glyphicon-filter:before{content:"\e138";}
        .glyphicon-fullscreen:before{content:"\e140";}
        .glyphicon-dashboard:before{content:"\e141";}
        .glyphicon-heart-empty:before{content:"\e143";}
        .glyphicon-link:before{content:"\e144";}
        .glyphicon-phone:before{content:"\e145";}
        .glyphicon-usd:before{content:"\e148";}
        .glyphicon-gbp:before{content:"\e149";}
        .glyphicon-sort:before{content:"\e150";}
        .glyphicon-sort-by-alphabet:before{content:"\e151";}
        .glyphicon-sort-by-alphabet-alt:before{content:"\e152";}
        .glyphicon-sort-by-order:before{content:"\e153";}
        .glyphicon-sort-by-order-alt:before{content:"\e154";}
        .glyphicon-sort-by-attributes:before{content:"\e155";}
        .glyphicon-sort-by-attributes-alt:before{content:"\e156";}
        .glyphicon-unchecked:before{content:"\e157";}
        .glyphicon-expand:before{content:"\e158";}
        .glyphicon-collapse-down:before{content:"\e159";}
        .glyphicon-collapse-up:before{content:"\e160";}
        .glyphicon-log-in:before{content:"\e161";}
        .glyphicon-flash:before{content:"\e162";}
        .glyphicon-log-out:before{content:"\e163";}
        .glyphicon-new-window:before{content:"\e164";}
        .glyphicon-record:before{content:"\e165";}
        .glyphicon-save:before{content:"\e166";}
        .glyphicon-open:before{content:"\e167";}
        .glyphicon-saved:before{content:"\e168";}
        .glyphicon-import:before{content:"\e169";}
        .glyphicon-export:before{content:"\e170";}
        .glyphicon-send:before{content:"\e171";}
        .glyphicon-floppy-disk:before{content:"\e172";}
        .glyphicon-floppy-saved:before{content:"\e173";}
        .glyphicon-floppy-remove:before{content:"\e174";}
        .glyphicon-floppy-save:before{content:"\e175";}
        .glyphicon-floppy-open:before{content:"\e176";}
        .glyphicon-credit-card:before{content:"\e177";}
        .glyphicon-transfer:before{content:"\e178";}
        .glyphicon-cutlery:before{content:"\e179";}
        .glyphicon-header:before{content:"\e180";}
        .glyphicon-compressed:before{content:"\e181";}
        .glyphicon-earphone:before{content:"\e182";}
        .glyphicon-phone-alt:before{content:"\e183";}
        .glyphicon-tower:before{content:"\e184";}
        .glyphicon-stats:before{content:"\e185";}
        .glyphicon-sd-video:before{content:"\e186";}
        .glyphicon-hd-video:before{content:"\e187";}
        .glyphicon-subtitles:before{content:"\e188";}
        .glyphicon-sound-stereo:before{content:"\e189";}
        .glyphicon-sound-dolby:before{content:"\e190";}
        .glyphicon-sound-5-1:before{content:"\e191";}
        .glyphicon-sound-6-1:before{content:"\e192";}
        .glyphicon-sound-7-1:before{content:"\e193";}
        .glyphicon-copyright-mark:before{content:"\e194";}
        .glyphicon-registration-mark:before{content:"\e195";}
        .glyphicon-cloud-download:before{content:"\e197";}
        .glyphicon-cloud-upload:before{content:"\e198";}
        .glyphicon-tree-conifer:before{content:"\e199";}
        .glyphicon-tree-deciduous:before{content:"\e200";}
        .glyphicon-briefcase:before{content:"\1f4bc";}
        .glyphicon-calendar:before{content:"\1f4c5";}
        .glyphicon-pushpin:before{content:"\1f4cc";}
        .glyphicon-paperclip:before{content:"\1f4ce";}
        .glyphicon-camera:before{content:"\1f4f7";}
        .glyphicon-lock:before{content:"\1f512";}
        .glyphicon-bell:before{content:"\1f514";}
        .glyphicon-bookmark:before{content:"\1f516";}
        .glyphicon-fire:before{content:"\1f525";}
        .glyphicon-wrench:before{content:"\1f527";}
    </style>

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