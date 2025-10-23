@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h3>Dashboard</h3>
<div class="alert alert-success">Selamat datang, <strong>{{ $username }}</strong>!</div>
@endsection
