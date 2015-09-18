
@extends("layouts.master")

@section("content")

<div class="col-md-offset-3 col-md-6">

<form  method="POST" action="/auth/register" class="form-horizontal">

{!! csrf_field() !!}

  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="name" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Name">
    </div>
  </div>

  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="email" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password"  name="password" class="form-control" placeholder="Password">
    </div>
  </div>

  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password Confirm</label>
    <div class="col-sm-10">
      <input type="password"  name="password_confirmation" class="form-control" placeholder="Password">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Register</button>
    </div>
  </div>
</form>

</div>

@stop

