@extends('layouts.master')

@section('content')

<div class="row">

@foreach ($links as $link)

    <div class="article">
        <div class="title"><a href="/{{ $link->slug }}">{{ $link->title }}</a></div>
        <div class="source"><a href="http://{{ $link->domain }}" target="_blank">{{ $link->domain }}</a></div>

        @if(count($link->keywords) > 0)
        <div class="related-links">
            <div class="related-matches">
    
                         @foreach (array_slice($link->keywords->toArray(), 0, 5) as $keyword) 
                            
                            <a href="/keywords/{{$keyword['slug']}}">{{ $keyword['text'] }}</a> |
                
                        @endforeach

                    </div>    
        </div>

        @endif
    </div>

@endforeach

</div>

@stop
