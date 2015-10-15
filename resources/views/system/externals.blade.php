@extends('layouts.master')

@section('content')

<div class="container">

    <div class="general-status">
            <div style="margin-top:80px; margin-bottom:30px">
              <span>{{count($externals)}} Links to be processed</span>
            </div>
    </div>
    
    <div class="status-table">
        <table class="table table-striped">

            @foreach($externals as $ext)

            <tr id="{{$ext['slug']}}" class=" @if( $ext['processed'])  @else danger @endif ">

                <td style="width: 80%;">{{ $ext['url'] }}
                <br> <small><a href="/{{ $ext['link']['slug'] }}" target="_blank">{{ $ext['link']['slug'] }}</a></small>
                </td>
                @if( $ext['processed'])
                <td class="text-right">Fetched <i class="fa fa-check"></i></td>
                @else
                <td class="text-right">Not fetched<i class="fa fa-exclamation-triangle"></i></td>
                @endif

            </tr>

            @endforeach
        </table>
    </div>


</div>

@stop
