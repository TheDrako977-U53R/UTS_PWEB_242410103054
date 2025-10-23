@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<h3>User Profile</h3>
<div class="card">
  <div class="card-body">
    <p><strong>Username:</strong> {{ $profile['username'] }}</p>
    <p><strong>Full Name:</strong> {{ $profile['nama_lengkap'] }}</p>
    <p><strong>Email:</strong> {{ $profile['email'] }}</p>
    <p><strong>Role:</strong> {{ $profile['role'] }}</p>
  </div>
</div>
@endsection
