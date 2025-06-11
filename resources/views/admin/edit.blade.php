@extends('layouts.admin-sidebar')

@section('content')
<div class="container">
    <h2>Edit Meeting Room</h2>

    @if(session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
    @endif

    <form action="{{ route('admin.meeting_rooms.update', $rooms) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Room Name</label>
            <input type="text" name="name" class="form-control" value="{{ $rooms->name }}" required>
        </div>

        <div class="mb-3">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" name="room_number" class="form-control" value="{{ $rooms->room_number }}" required>
        </div>

        <div class="mb-3">
            <label for="floor" class="form-label">Floor</label>
            <input type="text" name="floor" class="form-control" value="{{ $rooms->floor }}" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" value="{{ $rooms->capacity }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Room</button>
    </form>
</div>
@endsection
