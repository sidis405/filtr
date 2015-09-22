@extends('layouts.master')

@section('title', 'Search')
@section('content')

<div class="row">

<div class="full-search-results">
    <div class="row">
    @if($count >0)
        <div class="col-md-12">
            
            Looking for <strong>"{{$query}}"</strong> found {{$count}} results in {{$time/1000}}s

        </div> 

        <div class="col-md-12">

            @foreach ($results as $link)

                <div class="article">
                    <div class="title"><a href="/{{ $link['_source']['slug'] }}">{{ $link['_source']['title'] }}</a></div>
                    <div class="source"><a href="http://{{ $link['_source']['domain'] }}" target="_blank">{{ $link['_source']['domain'] }}</a></div>
                </div>

            @endforeach

        </div>
    @else

        <div class="col-md-12">
            
            Could not find any results matching "{{$query}}"

        </div> 

    @endif
    </div>
</div>

@stop