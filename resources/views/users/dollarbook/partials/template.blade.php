<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
     <!-- App css -->
     <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     <style>
        html {
           user-select: none;
        }

        .makeUnderLine{
            border-bottom: .5px solid #000 ; padding-bottom: 2px.
        }
     </style>
</head>
<body onload="window.print()" class="unselectable">
    <div class="container mt-5" style="font-size:27px;font-family: 'Times New Roman'">
        @include('users.dollarbook.partials.header')
        @yield('content')
        @include('users.dollarbook.partials.footer')    
    </div>
</body>
</html>