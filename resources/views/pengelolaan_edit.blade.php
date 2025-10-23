@extends('layouts.app')
@section('title', 'Edit Room')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h4 class="mb-3">Edit Room</h4>
  <form action="{{ route('pengelolaan.update', ['code' => $code]) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">Code (immutable)</label>
            <input type="number" class="form-control" value="{{ $item['Code'] }}" disabled>
            {{-- include hidden code field so the value is available if needed, but controller treats route param as authoritative --}}
            <input type="hidden" name="Code" value="{{ $item['Code'] }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Booker(s) Name</label>
            <input type="text" name="Booker(s) Name" class="form-control" value="{{ $item['Booker(s) Name'] }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Room Type</label>
            <input type="text" name="Room Type" class="form-control" value="{{ $item['Room Type'] }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="Available" {{ $item['status'] === 'Available' ? 'selected' : '' }}>Available</option>
              <option value="Booked" {{ $item['status'] === 'Booked' ? 'selected' : '' }}>Booked</option>
            </select>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('pengelolaan') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
