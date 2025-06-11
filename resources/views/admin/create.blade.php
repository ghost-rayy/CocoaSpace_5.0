@extends('layouts.admin-sidebar')

@section('content')
<div class="container">
    <h2>Add Meeting Room</h2>

    @if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
@endif

    <form action="{{ route('admin.storeRoom') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Room Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Room Number</label>
            <input type="text" name="room_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Floor</label>
            <input type="text" name="floor" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection

