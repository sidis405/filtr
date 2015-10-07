
        <div class="image-header">
            <div class="image" style="background-image:url('/images/users.jpg?w=500&h=100&fit=crop&blur=20'); background-size:cover; width:100%" class="img-responsive">
                <div class="image-header-overlay container">
                    <div class="user-header-info">
                        <img src="http://www.gravatar.com/avatar/{{ md5($user->email) }}" alt="{{$user->name}}" class="img-circle" style="width:120px;">
                        <h1> {{ $user->name }} </h1>
                        <h5 class="pull-left">Joined: {{ $user->created_at->format("F j, Y") }} </h5>
                    </div>
                    <div class="user-header-stats pull-right">
                        <div>Links shared: {{ count($user->links) }}</div>
                        <div>Entities followed: {{ count($user->entities) }} </div>
                        <div>Score: {{ floor($user->linksCount->aggregate*3 + count($user->entities)/2) }} </div>
                    </div>
                </div>
            </div>
        </div>
    
  

