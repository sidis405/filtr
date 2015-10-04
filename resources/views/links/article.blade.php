<div class="row single-article" id="{{ $link->slug }}">

    <div class="col-md-9">

        <h6>Original Link: <a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></h6>
        <h6>{{  $link->author_name }}</h6>

        <h1> {!! $link->title !!} </h1>
        <h5> Read time aprox <i class="fa fa-clock-o"></i> {{ $link->time_to_read }}m </h5>

        {!! $link->content !!}


        @if(count($related) > 0)
        <div class="related-links">
            <h5>Related</h5>
            <ul>
                @foreach ($related as $slug => $data) 
                    <li><a href="/{{$slug}}">{{ $data['info']['title'] }}</a> 
        
                    <div class="related-matches">
    

                        @foreach($data['matches'] as $match_slug => $match_data)
                            
                            <a href="/{{$match_data[0]['type']}}/{{$match_slug}}">{{$match_data[0]['text']}}</a> |
                
                        @endforeach

                    </div>    
            
                    </li>
                @endforeach
            </ul>

        </div>
        

        <div class="load-next" {{ $link->present()->getNextArticleForLoad(array_keys($related)) }}></div>
        @else
        <div class="load-next" data-load = "false"></div>
        


        @endif

    </div>

    <div class="col-md-3 sidebar" data-sticky_parent>
        
        @include('links.keywords-entities')

    </div>

</div>