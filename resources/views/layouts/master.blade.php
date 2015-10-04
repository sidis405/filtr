<!DOCTYPE html>
<html>
<head>
    <title>Filtr</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Rock+Salt|Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    
            
    <meta name="csrf_token" content="{{csrf_token()}}">
</head>
<body>

@include('layouts.main-bar')


<div class="container-custom">

    @include('layouts.errors')

    @include('flash::message')

    <div class="alert alert-success" id="page-refresher" style="display:none;">
      <span>Post processing is complete. Click on this alert to refresh the page</span>
    </div>

    <div class="alert alert-warning" id="page-processing" style="display:none;">
      <span>This article is now being processed. You will be notified as soon as Linkr is done working.</span>
    </div>

    @yield('content')

</div>

<!-- <script src="/js/withinviewport.js"></script> -->
<script src="/js/jquery-2.1.4.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<!-- <script src="/js/typeahead.jquery.js"></script> -->
<!-- <script src="/js/bloodhound.min.js"></script> -->
<!-- <script src="/js/bootstrap3-typeahead.min.js"></script> -->
<script src="/js/bootstrap-typeahead.js"></script>
<script src="/js/jquery.viewport.mini.js"></script>
<script src="/js/jquery.sticky-kit.min.js"></script>
<script src="/js/main.js"></script>

@yield('footer')

</body>
</html>
