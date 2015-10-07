@extends('layouts.master')

@section('title', $user->name)
@section('content')

<div id="entity-container">

    <div class="row article-header-container" style="    width: 101%!important;">
        @include('users.user-header')
    </div>

    


<div class="container" style="margin-top: -20px;">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>Entities Followed ({{count($user->entities)}})</h2>
        
                    <div class="hr-line-dashed"></div>
                        <div class="search-result">

                             @if(count($user->entities))


                            <ul class="sidebar-list">
                                    
                                @foreach($user->entities as $entity)
                                      

                                    <li><a href="/entities/{{$entity->slug}}" class="list-entities">{{ $entity->text }}</a></li>


                                @endforeach

                            </ul>
                            @else
                                <small>This user is not following any entities</small>
                            @endif
                        </div>

                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>
                        Articles shared
                    </h2>

                         @if(count($user->links) >0)
                            
                                  @foreach ($user->links as $link)

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="search-result">
                                                        <a href="/{{ $link->slug }}" class="pull-right"><img src="{{ $link->image }}?w=100" alt=""></a>
                                                    
                                                        <h4><a href="/{{ $link->slug }}">{{ $link->title }}</a></h4>
                                                        <small><a href="{{ $link->url }}" target="_blank" class="search-link">{{ $link->url }}</a></small>
                                                        <p>
                                                            {{ $link['description'] }}
                                                        </p>
                                                    </div>
                                @endforeach

                                 @else

                            <div class="col-md-12">
                                <div class="hr-line-dashed"></div>
                                    <div class="search-result">
                                        This user has not shared any articles yet.
                                    </div>    
                            </div> 

                        @endif

                </div>
            </div>
        </div>
    </div>
</div>
         

</div>

@stop




