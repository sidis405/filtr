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

            <ul>
                    
                @foreach($link->keywords as $keyword)
                        
                        <li><a href="/keyword/{{$keyword->slug}}">{{ $keyword->text }}</a></li>

                @endforeach

            </ul>

        </div>

        <div class="col-md-12">
            
            <h5>Entities</h5>

            <ul>
                    
                @foreach($link->entities as $entity)
                        
                        <li><a href="/entity/{{$entity->slug}}">{{ $entity->text }}</a></li>

                @endforeach

            </ul>

        </div>

    </div>

</div>

@stop