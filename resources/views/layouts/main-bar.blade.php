<div id="header-wrap">
    <div id="header" class="clear">
        <nav class="navbar navbar-fixed-top main-bar">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand logo" href="/"><span class="bracket">FILTR</span></a>
            </div>
            
            <div class="collapse navbar-collapse">
              <div class="btn-group navbar-left" role="group" >
                <button type="button" class="btn btn-default active" id="search-button">Search</button>
                <button type="button" class="btn btn-default" id="add-button">Add</button>
              </div>
              <form class="navbar-form navbar-left search-form" id="main-form" method="GET" action="/search">
                <div class="input-group">
                  <input type="search" name="q"  id="main-form-input" class="form-control main-form-input" value="{{\Request::input('q')}}" placeholder="Start typing to search"  autocomplete="off">
                  <span class="input-group-btn" style="width:1%">
                    <button class="btn btn-default"  id="main-form-button" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                  </span>
                </div>
              </form>
              <!-- <div id="progress-read">0</div> -->
              
              <div class="navbar-user">
                @if(isset($isSignedIn) && $isSignedIn)
                <ul class="nav navbar-nav" style="min-width:17%; float:right">
                    <li class="dropdown  btn btn-default" style="width:93%; padding: 2px 0!important">
                      <a href="#" class="dropdown-toggle user-profile-nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="http://www.gravatar.com/avatar/{{ md5($currentUser->email) }}" alt="{{$currentUser->name}}" class="img-circle" style="width:24px; margin-top: 2px;">
                        <span style="margin-top: 4px; float: left;     margin-left: 10px;">{{$currentUser->name}}</span>
                          <span class="caret " style="    margin-top: 11px; float: left; margin-left: 31px;"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="/users/{{$currentUser->id}}">Your Profile</a></li>
                        <li><a href="/status">System Status</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/logout">Logout</a></li>
                      </ul>
                    </li>
                </ul>

                @else
                <span class="pull-right">
                  <a class="btn btn-default" href="/login">Login</a>&nbsp;
                  <a class="btn btn-default" href="/register">Register</a>
                </span>
                @endif
              </div>
              </div><!--/.nav-collapse -->
              <div class="row" id="post_progressbar">
                <div style="height:3px;   margin-right: -4%;" class="progress">
                  <div id="progress-container" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" >
                    <div id="progressNumber"></div>
                  </div>
                </div>
                <div id="response"></div>
              </div>
            </div>
          </nav>
    </div>
</div>



