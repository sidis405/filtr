<div class="col-md-12">
            
            <h5>Keywords</h5>
            @if(count($link->keywords))
            
            <ul>
                    
                @foreach(array_slice($link->keywords->toArray(), 0, 10) as $keyword)
                        
                        <li><a href="/keyword/{{$keyword['slug']}}">{{ $keyword['text'] }}</a>&nbsp;&nbsp;<span class="label label-info pull-right">{{ floor($keyword['pivot']['relevance']*100) }}%</span></li>

                @endforeach

            </ul>
            @else
                <small>Filtr is working it's magic to process the keywords. You'll see them as soon as possible</small>
            @endif

        </div>

        <div class="col-md-12">
            <h5>Entities</h5>
            @if(count($link->entities))
            <ul>
                    
                @foreach(array_slice($link->entities->toArray(), 0, 10) as $entity)
                        
                        <li><a href="/entity/{{$entity['slug']}}">{{ $entity['text'] }}</a>&nbsp;&nbsp;<span class="label label-info pull-right">{{ floor($entity['pivot']['relevance']*100) }}%</span></li>

                @endforeach

            </ul>
            @else
                <small>Filtr is working it's magic to process the entities. You'll see them as soon as possible</small>
            @endif

        </div>