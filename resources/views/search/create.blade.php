@extends('layouts.master')

@section('title', 'Search')
@section('content')

<div class="row">

 <form class="form-inline global-search" role="form" action="/search">
                <div class="form-group">
                    <label class="sr-only" for="">Enter search terms</label>
                    <input type="search" class="form-control" id="q" name="q" placeholder="Enter search terms">
                </div>
                <button type="submit" id="s" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>

            </form>

</div>

@stop