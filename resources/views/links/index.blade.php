@extends('layouts.master')

@section('content')

<div class="row">
<div id="wrapper">
    <div id="columns">

@foreach ($links as $link)

<div class="pin">
            <a href="/{{ $link->slug }}"><img src="{{ $link->image }}"></a>
            <div class="title"><a href="/{{ $link->slug }}">{{ $link->title }}</a></div>
            <p>
                {{ $link-> description }}
            </p>
            <div class="related-links">
                    <div class="related-matches">
    
                         @foreach (array_slice($link->keywords->toArray(), 0, 3) as $keyword) 
                            
                            <span class="label label-info"><a href="/keywords/{{$keyword['slug']}}">{{ $keyword['text'] }}</a> </span>&nbsp;
                
                        @endforeach

                    </div>    
            </div>

            <div class="source"><a href="http://{{ $link->domain }}" target="_blank">{{ $link->domain }}</a></div>
        </div>

@endforeach

</div>
</div>
</div>

@stop
