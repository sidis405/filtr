<nav class="navbar navbar-fixed-top main-bar">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand logo" href="/"><span class="bracket">[</span>F<span class="bracket">]</span>ILTR</a>
      </div>
        
      <div class="collapse navbar-collapse">

      <div class="btn-group navbar-left" role="group" >

          <button type="button" class="btn btn-default active" id="search-button">Search</button>
          <button type="button" class="btn btn-default" id="add-button">Add</button>
    </div>



          <form class="navbar-form navbar-left search-form" id="main-form" method="GET" action="/search">

            <div class="input-group">
              <input type="search" name="q"  id="main-form-input" class="form-control main-form-input" value="{{\Request::input('q')}}" placeholder="Start typing to search"  autocomplete="off"> 
              <span class="input-group-btn">
                <button class="btn btn-default"  id="main-form-button" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </span>
            </div>
          </form>
    
        <div class="navbar-user">
          @if(isset($isSignedIn) && $isSignedIn)
            <button class="btn btn-default pull-right">{{$user->name}}</button>
          @else
            <a class="btn btn-default pull-right" href="/login">Login</a>
          @endif
        </div>
       </div><!--/.nav-collapse -->
</nav>
