@extends('layouts.app')
@section('title', 'Pengelolaan')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-2">
  <h3 class="mb-0">Data Room Management</h3>
  <div>
    <a href="{{ route('pengelolaan', array_merge(request()->query(), ['sort' => 'asc'])) }}" class="btn btn-sm btn-outline-secondary me-1">Sort ↑</a>
    <a href="{{ route('pengelolaan', array_merge(request()->query(), ['sort' => 'desc'])) }}" class="btn btn-sm btn-outline-secondary">Sort ↓</a>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title">Add Room</h5>
    <form action="{{ route('pengelolaan.add') }}" method="POST">
      @csrf
      <div class="row g-2">
        <div class="col-md-2">
          <input type="number" name="Code" class="form-control" placeholder="Code" required>
        </div>
        <div class="col-md-4">
          <input type="text" name="Booker(s) Name" class="form-control" placeholder="Booker(s) Name" required>
        </div>
        <div class="col-md-3">
          <input type="text" name="Room Type" class="form-control" placeholder="Room Type" required>
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select" required>
            <option value="Available">Available</option>
            <option value="Booked">Booked</option>
          </select>
        </div>
        <div class="col-md-1">
          <button class="btn btn-success w-100" type="submit">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr><th>Code</th><th>Booker(s)</th><th>Room Type</th><th>Status</th><th>Actions</th></tr>
  </thead>
  <tbody>
  @foreach ($baseItems as $item)
    <tr>
      <td>{{ $item['Code'] }}</td>
      <td>{{ $item['Booker(s) Name'] }}</td>
      <td>{{ $item['Room Type'] }}</td>
      <td>
        @if($item['status'] === 'Available')
          <span class="badge bg-success">Available</span>
        @else
          <span class="badge bg-danger">Booked</span>
        @endif
      </td>
      <td>
        <a href="{{ route('pengelolaan.edit', ['code' => $item['Code']]) }}" class="btn btn-sm btn-primary me-1">Edit</a>
        <form action="{{ route('pengelolaan.delete', ['code' => $item['Code']]) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this item?');">
          @csrf
          <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
