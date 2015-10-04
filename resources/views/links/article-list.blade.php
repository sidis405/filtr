@foreach ($links as $link)

<div class="pin">
            <a href="/{{ $link->slug }}"><img class="img-responsive" src="{{ $link->image }}"></a>
            <div class="title" style="width:100%"><a href="/{{ $link->slug }}">{{ $link->title }}</a></div>
            <p>
                {{ $link-> description }}
            </p>

            @if( ! isset($preview) )
            <div class="related-links">
                    <div class="related-matches">
    
                         @foreach (array_slice($link->keywords->toArray(), 0, 3) as $keyword) 
                            
                            <span class="label label-info"><a href="/keywords/{{$keyword['slug']}}">{{ $keyword['text'] }}</a> </span>&nbsp;
                
                        @endforeach

                    </div>    
            </div>
            @endif

            <div class="source"><a href="http://{{ $link->domain }}" target="_blank">{{ $link->domain }}</a></div>
            <div class="timer"><i class="fa fa-clock-o"></i> {{ $link->time_to_read }}m</div>
        </div>

@endforeach