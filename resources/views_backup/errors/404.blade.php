<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>
<style>
    .pagenotfound{
        font-family:Arial, Helvetica, sans-serif;
        text-align:center;
        padding:5% 0;
        color:#000;
        }
    .pagenotfound h1{
        font-size:120px;
        line-height:80px;
        color:#666;
        }   
    .pagenotfound h2{
        font-size:50px;
        line-height:40px;
        color:#333;
        }
    .pagenotfound p{
        font-size:16px;
        color:#666;
        }
    .pagenotfound .btn-submit a{
        display:inline-block;
        color:#fff;
        background-color:#812d8b;
        padding:15px 30px;
        border-radius:8px;
        text-decoration:none;
        margin-top:30px;
        }
    .pagenotfound .btn-submit a:hover{
        background-color:#FF7F00;
        }
    @media (max-width: 767px){
        .pagenotfound h1{font-size:100px;line-height:60px;}
        .pagenotfound h2{font-size:30px;line-height:30px;}
        }                       
</style>
<body>
<div class="pagenotfound">
    <h1>404</h1>
    <h2>PAGE NOT FOUND.</h2>
    <p>Please try one of the following pages.</p>
    <div class="btn-submit">
        <a href="{{ route('home') }}">HOME PAGE</a>
    </div>
</div><!--end pagenotfound-->
</body>
</html>
