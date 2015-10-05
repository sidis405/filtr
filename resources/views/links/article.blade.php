<div class="single-article"  id="{{ $link->slug }}" data-title="{{ $link->title }}">
    <div class="row article-header-container">
            @include('links.article-header')
    </div>

    <div class="container">

        <div class="col-md-9">


            {!! $link->content !!}


            @if(count($related) > 0)
            <div class="related-links">
                <h5>Related</h5>
                <ul>
                    @foreach (array_slice($related, 0, 3) as $slug => $data) 
                        <li><a href="/{{$slug}}">{{ $data['info']['title'] }}</a> 
            
                        <div class="related-matches">
        

                            @foreach(array_slice($data['matches'], 0, 8) as $match_slug => $match_data)
                                
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

</div>
