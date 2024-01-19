<!doctype html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title>Good Amenity Preview</title>
    <!--[if IE 8]><link rel='stylesheet' media='screen' href='css/ie8.css?v=201403261325' type='text/css' /><![endif]-->
    <link rel="stylesheet" media="screen" href="{{ asset('front/preview/css/main.css?v=9') }}">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="shortcut icon" type="image/ico" href="{{ asset('front/images/favicon.ico') }}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style type="text/css">
        /* Style the tab */
        div.tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        div.tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        div.tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        div.tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
    </style>

</head>
<body>
    <section id='devices'>

        <div class="tab">
          <button class="tablinks active" onclick="openCity(event, 'London')">iPhone 6 portrait</button>
          <button class="tablinks" onclick="openCity(event, 'Paris')">iPhone 6 landscape</button>
          <button class="tablinks" onclick="openCity(event, 'Tokyo')">iPad portrait</button>
          <button class="tablinks" onclick="openCity(event, 'iPadLandscape')">iPad landscape</button>
          <button class="tablinks" onclick="openCity(event, 'Desktop')">Desktop</button>
        </div>

        <div id="London" class="tabcontent" style="display: block;">
            <div class='deviceWrap iphone-6-portrait'>
                <div class='device' style='
                    width: 375px;
                    height: 603px;
                    padding-top: 20px;padding-bottom: 44px;'>
                        <div class='flashingTop' style='height: 20px; width: 375px'>
                            <span class='time'></span>
                        </div>
                    <div class='flashingBottom' style='height: 44px'>
                    </div><iframe src='{{ $url }}' id='iphone-6-portrait' style=''></iframe></div>
            </div>
            <div class='deviceTitle'>
                <a href="#"><em>iPhone 6 portrait<span> · width: 375px</span></em></a>
            </div>
        </div>

        <div id="Paris" class="tabcontent">
            <div class='deviceWrap iphone-6-landscape'>
                <div class='device' style='
                    width: 667px;
                    height: 311px;
                    padding-top: 20px;            padding-bottom: 44px;                                    '><div class='flashingTop' style='height: 20px; width: 667px'><span class='time'></span></div><div class='flashingBottom' style='height: 44px'></div><iframe src='{{ $url }}' id='iphone-6-landscape' style='
                                                                                        '></iframe></div>
            </div>
            <div class='deviceTitle'>
                <a href="#"><em>iPhone 6 landscape<span> · width: 667px</span></em></a>
            </div>
        </div>

        <div id="Tokyo" class="tabcontent">
            <div class='deviceWrap ipad-portrait'>
                <div class='device' style='
                    width: 768px;
                    height: 929px;
                    padding-top: 95px;'>
                <div class='flashingTop' style='height: 95px; width: 768px'><span class='time'></span></div><iframe src='{{ $url }}' id='ipad-portrait' style=''></iframe></div><!-- /device -->

            </div><!-- /deviceWrap -->

            <div class='deviceTitle'>
                <a href="#"><em>iPad portrait<span> · width: 768px</span></em></a>
            </div>

        </div>

        <div id="iPadLandscape" class="tabcontent">
            <div class='deviceWrap ipad-landscape'>
                <div class='device' style='
                    width: 1024px;
                    height: 675px;
                    padding-top: 93px;                                                '><div class='flashingTop' style='height: 93px; width: 1024px'><span class='time'></span></div><iframe src='{{ $url }}' id='ipad-landscape' style='
                                                                                        '></iframe></div>        <!-- /device -->

            </div><!-- /deviceWrap -->

            <div class='deviceTitle'>
                <a href="#"><em>iPad landscape<span> · width: 1024px</span></em></a>
            </div>       
        </div>

        <div id="Desktop" class="tabcontent">
            <div style="height: 1024px;">
                    <iframe src='{{ $url }}' id='ipad-landscape'></iframe>
            </div>  
        </div>

    </section>

    <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type='text/javascript' src="{{ asset('preview/libs/mixpanel-2.2.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/responsinator.min.js?v=201403261325') }}"></script>

    <script type="text/javascript">
        mixpanel.track_links("#nav-responsinator", "nav home", {
            "referrer": document.referrer
        });
        mixpanel.track_links("#nav-make-your-own", "nav make your own", {
            "referrer": document.referrer
        });
        mixpanel.track_links("#nav-about", "nav about", {
            "referrer": document.referrer
        });
        mixpanel.track_links("#nav-login", "nav login", {
            "referrer": document.referrer
        });

        mixpanel.track(
        "entered-website",
            { "header": "ok" }
        );

        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        $(function() {
            alert("OK");
        });

    </script>

</body>
</html>
