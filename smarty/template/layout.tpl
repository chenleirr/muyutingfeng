<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>沐雨听风阁</title>

    <link rel="shortcut icon" href="/web/custom/img/icon/yun.ico">
    <link href="/web/custom/css/w3.css" rel="stylesheet" />
    <link href="/web/custom/css/custom.css" rel="stylesheet" />
    <script
        src="http://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous">
    </script>
    <script src="http://s1.bdstatic.com/r/www/cache/ecom/esl/2-0-4/esl.js"></script>
    {%block name="css"%}{%/block%}
    {%block name="js"%}{%/block%}

</head>
<body>

<div class="w3-sidebar w3-bar-block w3-card-2 w3-animate-left" style="display:none;z-index:4" id="mySidebar">
    <a href="/"><img class="custom_img1" src="/web/custom/img/icon/Doggy.jpg"></a>
    <a href="/" class="custom_button1"><span class="custom_span1">首页</span></a>
    <div class="dropdown">
        <a href="#" class="custom_button1"><span class="custom_span1">技术笔记</span></a>
        <div class="dropdown-content">
            <a href="/notes/php" class="custom_a1">PHP</a>
            <a href="/notes/laravel" class="custom_a1">Laravel</a>
            <a href="/nodes/mysql" class="custom_a1">Mysql</a>
        </div>
    </div>
    <a href="/" class="custom_button1" style="vertical-align:middle"><span class="custom_span1">别点这儿 </span></a>
</div>

<div id="myOverlay" class="w3-overlay" onclick="w3_close()" style="cursor:pointer"></div>


<div class="w3-container w3-teal w3-blue chenlei">
    <button class="w3-button w3-round-xlarge w3-border w3-border-white buttonPosition" onclick="w3_open()">Menu</button>
    <div class="titleContainer">
        <h1><div id="main_title">沐雨听风阁</div></h1>
        <h4><div id="sub_title">Keep going!</div></h4>
    </div>
</div>

<div>
    {%block name="main"%}{%/block%}
</div>

<script>
    function w3_open() {
        document.getElementById("mySidebar").style.display = "block";
        document.getElementById("myOverlay").style.display = "block";
    }
    function w3_close() {
        document.getElementById("mySidebar").style.display = "none";
        document.getElementById("myOverlay").style.display = "none";
    }
</script>



</body>
</html>
