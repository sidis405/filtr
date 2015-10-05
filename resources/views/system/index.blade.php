@extends('layouts.master')

@section('content')

<div class="container">

    <div class="general-status">
        @if($services['failing'] == 0)
            <div class="alert alert-success" style="margin-top:80px">
              <span>All Systems are operational</span>
              <span class="pull-right"><i class="fa fa-check"></i></span>
            </div>
        @else
            <div class="alert alert-danger" style="margin-top:80px">
              <span><strong>{{ $services['failing'] }}</strong> systems are failing. See table below.</span>
              <span class="pull-right"><i class="fa fa-exclamation-triangle"></i></span>
            </div>
        @endif
    </div>
    
    <div class="status-table">
        <table class="table table-striped">

            <?php array_shift($services); ?>

            @foreach($services as $service)

            <tr class=" @if( $service['running'])  @else danger @endif ">
                <td>{{ $service['label'] }}</td>
                @if( $service['running'])
                <td class="text-right">Operational <i class="fa fa-check"></i></td>
                @else
                <td class="text-right">{{$service['error_label']}}. Severity: <strong>{{$service['error_severity']}}</strong> <i class="fa fa-exclamation-triangle"></i></td>
                @endif
            </tr>

            @endforeach
        </table>
    </div>


</div>

@stop
