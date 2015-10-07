@extends('layouts.master')

@section('title', 'User list')
@section('content')

<div id="entity-container">

    <div class="row article-header-container" style="    width: 101%!important;">
        @include('users.userlist-header')
    </div>

    


<div class="container" style="margin-top: -20px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">

                         @if(count($users) >0)
                            
                                  @foreach ($users as $user)

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="search-result">
                                                        <a href="/users/{{ $user->id }}" class="pull-right">
                                                            <img src="http://www.gravatar.com/avatar/{{ md5($user->email) }}" style="width:100px">
                                                        </a>
                                                    
                                                        <h4><a href="/users/{{ $user->id }}">{{ $user->name }}</a></h4>
                                                        <p>
                                                            Score: {{ floor(count($user->links)*3 + count($user->entities)/2) }}
                                                        </p>
                                                    </div>
                                @endforeach

                                 @else

                            <div class="col-md-12">
                                <div class="hr-line-dashed"></div>
                                    <div class="search-result">
                                        <small>There are no registered users.<small>
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




