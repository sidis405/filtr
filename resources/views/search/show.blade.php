@extends('layouts.master')

@section('title', 'Search')
@section('content')

<div class="container full-search-results">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>
                        {{$count}} results found for: <span class="text-navy">"{{$query}}"</span>
                    </h2>
                    <small>Request time  ({{$time/1000}} seconds)</small>
                         @if($count >0)
                            
                                  @foreach ($results as $link)
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="search-result">
                                                        <a href="/{{ $link['_source']['slug'] }}" class="pull-right"><img src="{{ $link['_source']['image'] }}?w=100" alt=""></a>
                                                    
                                                        <h4><a href="/{{ $link['_source']['slug'] }}">{{ $link['_source']['title'] }}</a></h4>
                                                        <small><a href="{{ $link['_source']['url'] }}" target="_blank" class="search-link">{{ $link['_source']['url'] }}</a></small>
                                                        <p>
                                                            {{ $link['_source']['description'] }}
                                                        </p>
                                                    </div>
                                @endforeach

                                 @else

                            <div class="col-md-12">
                                
                                Could not find any results matching "{{$query}}"

                            </div> 

                        @endif

                </div>
            </div>
        </div>
    </div>
</div>
         

@stop
