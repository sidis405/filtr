@extends('layouts.master')

@section('content')
    <p id="power">0</p>
@stop

@section('footer')
    <script src="{{ asset('/js/socket.io.js') }}"></script>

    <script>
    var socket = io('http://127.0.0.1:6001');
    socket.on("test-channel:App\\Events\\EventName", function(message){
         console.log('received 10');
         $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
     });

    socket.on("ping", function(message){
         console.log(message);
     });
    </script>
@stop