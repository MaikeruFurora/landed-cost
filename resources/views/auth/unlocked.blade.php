<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Frames</title>
    <style>
        body {
            background: #000000;
            color: #FFFFFF;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            font-family: 'Roboto Mono', monospace;
        }

        .frame-container {
            margin-top: 20px;
            padding-left: 200px;
            padding-right: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .wave {
            margin: 20px;
            animation: wave ease-in-out 1s infinite alternate;
            transform-origin: center -36px;
            position: relative;
        }

        .wave:hover {
            animation-play-state: paused;
            cursor: pointer;
        }

        .wave img {
            border: 5px solid #f8f8f8;
            display: block;
            width: 200px;
            height: 250px;
        }

        .wave figcaption {
            text-align: center;
        }

        .wave:after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 1.5px solid #ffffff;
            top: -10px;
            left: 50%;
            border-bottom: none;
            border-right: none;
            transform: translateX(-50%) rotate(35deg);
        }

        .wave:before {
            content: '';
            position: absolute;
            top: -23px;
            left: 50%;
            display: block;
            height: 44px;
            width: 47px;
            background-image: url(https://cdn.hashnode.com/res/hashnode/image/upload/v1602040503586/FW6g79WBY.png);
            background-size: 20px 20px;
            background-repeat: no-repeat;
            transform: translateX(-50%);
            z-index: 16;
        }

        @keyframes wave {
            0% { transform: rotate(10deg); }
            100% { transform: rotate(-10deg); }
        }

        /* Heartbeat effect */
        @keyframes heartbeat {
            0%   { transform: scale(1); }
            14%  { transform: scale(1.3); }
            28%  { transform: scale(1); }
            42%  { transform: scale(1.3); }
            70%  { transform: scale(1); }
            100% { transform: scale(1); }
        }

        h1 {
            color: #ffffff;
            font-size: 48px;
            animation: heartbeat 7s infinite;
        }
    </style>
</head>
<body>
    <h1>Successfully Unlocked (SAP) <img  style="margin-top: 30px;" src="https://upload.wikimedia.org/wikipedia/commons/5/59/SAP_2011_logo.svg" width="50" height="50"></h1>
    <h3>Boy refresh; minsan virus</h3>
    <div class="frame-container">
        <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/chris.png") }}" alt="chris">
            <figcaption>Chris Brown</figcaption>
        </figure>

        <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/joseph.png") }}" alt="joseph Carido">
            <figcaption>Joseph</figcaption>
        </figure>

        <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/dan.png") }}" alt="Dann">
            <figcaption>Dann</figcaption>
        </figure>

        <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/josh.png") }}" alt="Joshua">
            <figcaption>Joshua</figcaption>
        </figure> 
         <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/loyd.png") }}" alt="bean">
            <figcaption>Loyd</figcaption>
        </figure>

        <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/marco.png") }}" alt="Marco">
            <figcaption>Marco Polo</figcaption>
        </figure>
        <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/jl.png") }}" alt="JL">
            <figcaption>JL the Great</figcaption>
        </figure>
         <figure class="wave">
            <img src="{{ asset("assets/icons/css/itb/mike.png") }}" alt="bean">
            <figcaption>Mike Mouse</figcaption>
        </figure>
         
    </div>

</body>
</html>
