
        <div class="image-header">
            <div class="image" style="background-image:url('/images/entities.jpg?w=500&h=100&fit=crop&blur=20'); background-size:cover; width:100%" class="img-responsive">
                <div class="image-header-overlay container">
                    <h1> {{ $entity->text }} </h1>
                    <h5 class="pull-left"> {{ $entity->type }} </h5>
                    
                    @if(strlen ( $entity->screenshot) > 2 )
                    <div class="pull-right screenshot-container" >
                        <a class="image-header-overlay-link" href="{{$entity->website}}" target="blank">
                            <img src="{{$entity->screenshot}}" class="screenshot">
                            <a class="btn btn-default btn-info" style="text-shadow: none; width:100%" class="image-header-overlay-link" href="{{$entity->website}}" target="blank">Visit website</a>
                        </a>
                    </div>
                    @else
                        @if(strlen ( $entity->website) > 2 )
                            <div class="pull-right" >
                                    <a class="btn btn-default btn-info" style="text-shadow: none; width:100%" class="image-header-overlay-link" href="{{$entity->website}}" target="blank">Visit website</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    
  

