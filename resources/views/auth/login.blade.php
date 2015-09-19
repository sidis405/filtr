@extends('layouts.master')

@section('content')

<div class="admin-container col-md-offset-5 col-md-2">


<form  method="POST" action="/auth/login" class="form-horizontal">

{!! csrf_field() !!}

  <div class="form-group">
    <label for="email" class="col-sm-12 login-label control-label">Email</label>
    <div class="col-sm-12">
      <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="email" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-12 login-label control-label">Password</label>
    <div class="col-sm-12">
      <input type="password"  name="password" class="form-control" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-default btn-success col-sm-12">Login</button>
    </div>
  </div>
</form>

</div>

@stop

