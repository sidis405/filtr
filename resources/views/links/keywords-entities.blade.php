<div class="col-md-12">
    

    <div class="article-userinfo">
        <div>
            <img src="http://www.gravatar.com/avatar/{{ md5($link->user->email) }}" alt="{{$link->user->name}}" class="img-circle pull-right" style="width:40px">
        </div>
        <div>{{ $link->user->name }}</div>
        <small>{{ $link->created_at->format("F j, Y, g:i a") }}</small>
    </div>

</div>

<div class="col-md-12">
            
            <h5>Keywords</h5>
            @if(count($link->keywords))
            
            <ul class="sidebar-list">
                    
                @foreach(array_slice($link->keywords->toArray(), 0, 10) as $keyword)
                        @if(floor($keyword['pivot']['relevance']*100) > 50)  
                        <li><a href="/keyword/{{$keyword['slug']}}">{{ $keyword['text'] }}</a>&nbsp;&nbsp;<span class="label label-info pull-right">{{ floor($keyword['pivot']['relevance']*100) }}%</span></li>
                        @endif
                @endforeach

            </ul>
            @else
                <small>Filtr is working it's magic to process the keywords. You'll see them as soon as possible</small>
            @endif

        </div>

        <div class="col-md-12">
            <h5>Entities</h5>
            @if(count($link->entities))
            <ul class="sidebar-list">
                    
                @foreach(array_slice($link->entities->toArray(), 0, 10) as $entity)
                      
                      @if(floor($entity['pivot']['relevance']*100) > 50)  
                        <li><a href="/entity/{{$entity['slug']}}" class="entities list-entities">{{ $entity['text'] }}</a>&nbsp;&nbsp;<span class="label label-info pull-right">{{ floor($entity['pivot']['relevance']*100) }}%</span></li>
                      @endif

                      @include('links.entity-hovercard', array('entity->entity'))
                @endforeach

            </ul>
            @else
                <small>Filtr is working it's magic to process the entities. You'll see them as soon as possible</small>
            @endif

        </div>

       

            {{-- */$links = $link->present()->getNextArticlePreview($related);/* --}}

            @if(count($links) > 0)
            
            <div class="col-md-12 next-preview" id="next-preview">
                
                <h5>Coming Up Next</h5>

                @include('links.article-list', ['preview' => true])

            </div>

            @endif

