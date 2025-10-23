@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="mb-3 text-center">Form Login</h4>
        <form action="{{ route('login.process') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          @if($errors->has('login'))
          <div class="alert alert-danger mb-3">
            {{ $errors->first('login') }}
          </div>
          @endif
          <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
