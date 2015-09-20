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
          <form class="navbar-form" method="POST" action="/">
            {{csrf_field()}}
            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button> 
            <input type="search" name="url" class="form-control pull-left" value="http://www.wired.co.uk/magazine/archive/2015/03/start/scaling-dublin-summit"> 
          </form>
       </div><!--/.nav-collapse -->
</nav>
