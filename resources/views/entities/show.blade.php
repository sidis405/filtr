@extends('layouts.master')

@section('content')

<div id="entity-container">

    <div class="row article-header-container" style="    width: 101%!important;">
        @include('entities.entity-header')
    </div>

    


<div class="container" style="margin-top: -20px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>
                        {{count($entity->links)}} results found related to <span class="text-navy">"{{$entity->text}}"</span>
                    </h2>

                         @if(count($entity->links) >0)
                            
                                  @foreach ($entity->links as $link)
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="search-result">
                                                        <a href="/{{ $link['slug'] }}" class="pull-right"><img src="{{ $link['image'] }}?w=100" alt=""></a>
                                                    
                                                        <h4><a href="/{{ $link['slug'] }}">{{ $link['title'] }}</a></h4>
                                                        <small><a href="{{ $link['url'] }}" target="_blank" class="search-link">{{ $link['url'] }}</a></small>
                                                        <p>
                                                            {{ $link['description'] }}
                                                        </p>
                                                    </div>
                                @endforeach

                                 @else

                            <div class="col-md-12">
                                
                                Could not find any results matching "{{$entity->text}}"

                            </div> 

                        @endif

                </div>
            </div>
        </div>
    </div>
</div>
         

</div>

@stop




