@extends('layouts.master')

@section('content')

<div class="row">

@foreach ($links as $link)

    <div class="article">
        <div class="title"><a href="/{{ $link->slug }}">{{ $link->title }}</a></div>
        <div class="source"><a href="http://{{ $link->domain }}" target="_blank">{{ $link->domain }}</a></div>
    </div>

@endforeach

</div>

@stop