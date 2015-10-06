<div class="entity-hovercard" style="display:none" id="entity_{{$entity['slug']}}">

<div class="hovercard">
    <div>
        <div class="display-pic">
            <div class="cover-photo">
            <div class="display-pic-gradient"></div><img src="/images/entities.jpg?w=370&h=90&fit=crop&blur=20" style="height:100%"></div>

            <div class="profile-pic">
            @if(strlen($entity['screenshot']))
                <div class="pic"><img src="{{$entity['screenshot']}}" title=
                "Profile Image"></div>
            @else
        
                <div class="pic"><img src="/img/filtr.png" title=
                "Profile Image"></div>

            @endif

                <div class="details">
                    <ul class="details-list">

                        @foreach(array_slice($entity['links'], 0, 2) as $link)

                        <li class="details-list-item">
                            <p> 
                                <a href="/{{ $link['slug'] }}">{{$link['title']}}</a></span>
                            </p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="display-pic-gradient"></div>

        <div class="title-container">
            <a class="title" href="/entities/{{$entity['slug']}}" title="Visit Page">{{ $entity['text'] }}</a>

            <p class="other-info">{{$entity['type']}}</p>
        </div>

        <div class="info">
            <div class="info-inner">
                <div class="interactions">
                    @if($user)
                        {!! $user->present()->entityFollowButton($entity['id']) !!}
                    @else
                        <a class="btn" disabled>Follow</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    

</div>