@extends('layouts.master')

@section('title', 'Search')
@section('content')

<div class="row">

 <form class="form-inline main-search" role="form">
                <div class="form-group" style="    width: 95%; margin-left: 1%;">
                    <label class="sr-only" for="">Enter search terms</label>
                    <input style="width:100%" type="search" class="form-control" id="q" name="q" value="{{$query}}" placeholder="Enter search terms" autocomplete="off">
                </div>
                <button type="submit" id="s" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>

            </form>

</div>

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