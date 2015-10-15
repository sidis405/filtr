@foreach ($links as $link)

<div class="pin">
            <a href="/{{ $link->slug }}"><img class="img-responsive" src="{{ $link->image }}?w=200"></a>
            <div class="title" style="width:100%"><a href="/{{ $link->slug }}">{{ $link->title }}</a></div>
            <p>
                {{ substr($link-> description, 0, 200) }}
            </p>

            @if( ! isset($preview) )
            <div class="related-links">
                    <div class="related-matches">
    
                         @foreach (array_slice($link->entities->toArray(), 0, 3) as $entity) 
                            
                            <span class="label label-info"><a href="/entities/{{$entity['slug']}}">{{ $entity['text'] }}</a> </span>&nbsp;
                
                        @endforeach

                    </div>    
            </div>
            @endif

            <div class="source"><a href="http://{{ $link->domain }}" target="_blank">{{ $link->domain }}</a></div>
            <div class="timer"><i class="fa fa-clock-o"></i> {{ $link->time_to_read }}m</div>
        </div>

@endforeach