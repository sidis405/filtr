    @if( $link->status == 1 && strlen($link->image) > 5 )
        <div class="image-header">
            <div class="image" style="background-image:url('{{$link->image}}?w=500&h=100&fit=crop&blur=20'); background-size:cover; width:100%" class="img-responsive">
                <div class="image-header-overlay container">
                    <h1> {!! $link->title !!} </h1>
                    <h5> Read time aprox <i class="fa fa-clock-o"></i> {{ $link->time_to_read }}m  &nbsp;&nbsp;&nbsp; Seen <i class="fa fa-eye"></i> {{ $link->read_counter }} times </h5>
                    <h5 class="pull-right "> <a class="image-header-overlay-link" href="http://{{$link->domain}}" target="blank">{{ $link->domain }}</a></h5>
                </div>
            </div>
        </div>
    
        <div class="container article-header-info">
            <h6 class="pull-left"><i class="fa fa-external-link"></i>&nbsp;Original Link: <a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></h6>
            <h6 class="pull-left">&nbsp {{  $link->author_name }}</h6>
        </div>

    @else

        <div class="container article-header-info">
            <h6 class="pull-right"><i class="fa fa-external-link"></i>&nbsp;Original Link: <a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></h6>
            <h6 class="pull-left">&nbsp {{  $link->author_name }}</h6>

            <h1> {!! $link->title !!} </h1>
            <h5> Read time aprox <i class="fa fa-clock-o"></i> {{ $link->time_to_read }}m </h5>
        </div>

    @endif