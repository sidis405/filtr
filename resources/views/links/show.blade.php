@extends('layouts.master')

@section('title', '- ' . $title)
@section('content')

<div id="article-container">

    @include('links.article')

</div>

@stop

@section('footer')
    @include('links.links-footer-scripts')
@stop




