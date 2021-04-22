<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 | Error</title>
    <link rel="icon" href="{{asset(env('ICON_PATH'))}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'style.min.css')}}">
    <style>
        body {
            margin: 0;
            padding: 0;
            /*background-color: #FBFCFC;*/
            height: 100%;
            width: 100%;
        }
        #bg {
            z-index: -9;
            opacity: 0.45;
            position: absolute;
            background-image: url("{{asset('sub/images/patternBNW.png')}}");
            width: 100vw;
            height: 100vh;
        }
        #container {
            margin: 0 auto;
            width: 30vw;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        h1 {
            font-size: 40pt;
        }
        .void-area {
            flex: 1;
        }
        .fill-area {
            flex: 0;
        }
    </style>
</head>
<body>
<div id="bg"></div>
<div id="container" class="">
    <div class="void-area"></div>
    <div class="fill-area">
        <h1 class="text-danger"><strong>Kesalahan!</strong></h1>
        <h2 class="text-danger"><strong>404 Halaman Tidak Ditemukan</strong></h2>
        <p class="text-justify text-dark">
            Maaf sepertinya halaman yang ingin anda tuju tidak ada. Mohon untuk memeriksa kembali URL halaman tersebut.
        </p>
    </div>
    <div class="void-area"></div>
</div>
</body>
</html>
