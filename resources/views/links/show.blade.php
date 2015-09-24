@extends('layouts.master')

@section('content')

<div class="row">

    <div class="col-md-9">

        <h6>Original Link: <a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></h6>

        <h1> {!! $link->title !!} </h1>

        {!! $link->content !!}


        @if(count($related) > 0)
        <div class="related-links">
            <h5>Related</h5>
            <ul>
                @foreach ($related as $slug => $data) 
                    <li><a href="/{{$slug}}">{{ $data['info']['title'] }}</a> 
        
                    <div class="related-matches">
    

                        @foreach($data['matches'] as $match_slug => $match_data)
                            
                            <a href="/{{$match_data[0]['type']}}/{{$match_slug}}">{{$match_data[0]['text']}}</a> |
                
                        @endforeach

                    </div>    
            
                    </li>
                @endforeach
            </ul>

        </div>

        @endif

    </div>

    <div class="col-md-3">
        
        <div class="col-md-12">
            
            <h5>Keywords</h5>
            @if(count($link->keywords))
            
            <ul>
                    
                @foreach($link->keywords as $keyword)
                        
                        <li><a href="/keyword/{{$keyword->slug}}">{{ $keyword->text }}</a></li>

                @endforeach

            </ul>
            @else
                <small>Filtr is working it's magic to process the keywords. You'll see them as soon as possible</small>
            @endif

        </div>

        <div class="col-md-12">
            <h5>Entities</h5>
            @if(count($link->entities))
            <ul>
                    
                @foreach($link->entities as $entity)
                        
                        <li><a href="/entity/{{$entity->slug}}">{{ $entity->text }}</a></li>

                @endforeach

            </ul>
            @else
                <small>Filtr is working it's magic to process the entities. You'll see them as soon as possible</small>
            @endif

        </div>

    </div>

</div>

@stop

@section('footer')
    <script src="{{ asset('/js/socket.io.js') }}"></script>

    <script>
    var socket = io('http://127.0.0.1:6001');
    socket.on("link_{{$link->id}}:App\\Events\\Links\\LinkWasProcessed", function(message){
         
         if(message.data.command == 'reload')
         {
            alert('post processing is done. time to refresh the page');
         }
         
     });

    socket.on("ping", function(message){
         console.log(message);
     });
    </script>
@stop




