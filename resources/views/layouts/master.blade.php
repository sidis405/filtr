<!DOCTYPE html>
<html>
<head>
    <title>Filtr</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <meta name="csrf_token" content="{{csrf_token()}}">
</head>
<body>

@include('layouts.main-bar')


<div class="container">

    @include('layouts.errors')

    @yield('content')

</div>

<script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
</body>
</html>