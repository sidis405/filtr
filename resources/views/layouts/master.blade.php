<!DOCTYPE html>
<html>
<head>
    <title>Filtr</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">

    body {
        font-size: 16px!important;
    }

    img {
    width: 50%;
    height: auto;
    float: left;
    margin-right: 2%;
    margin-bottom: 1%;
    }
    
    .navbar-form input {
        width:90%!important;
    }

    .container {
        margin-top: 80px!important;
    }

    </style>
</head>
<body>

@include('layouts.search')


<div class="container">

    @include('layouts.errors')

    @yield('content')

</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>