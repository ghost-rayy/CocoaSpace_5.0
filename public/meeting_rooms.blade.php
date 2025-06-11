@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="container">
    <h1>Meeting Rooms</h1>
    <a href="{{ route('admin.create') }}" class="btn btn-success">Add Meeting Room</a>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonText: 'Done',
                confirmButtonColor: '#42CCC5',
                background: '#fff',
                iconColor: '#42CCC5'
            });
        </script>
        @endif

    <br><br><div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Room Number</th>
                    <th>Floor</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                <tr>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->floor }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>
                        <a href="{{ route('admin.meeting_rooms.edit', $room->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.meeting_rooms.destroy', $room->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

<style>
    /* General Styles */
    * {
        box-sizing: border-box;
    }
    body {
        font-family: Helvetica, sans-serif;
        background: rgba(71, 147, 227, 1);
    }

    h1 {
        text-align: center;
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        padding: 30px 0;
    }

    .table-wrapper {
        max-height: 700px;
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .fl-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fl-table thead th {
        position: sticky;
        top: 0;
        background-color: #42CCC5;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 12px;
        z-index: 2;
    }

    .fl-table th, .fl-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f4faff;
    }

    .fl-table tbody tr:hover {
        background-color: #e0f0ff;
    }

    /* Optional: Improve responsiveness */
    @media (max-width: 768px) {
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>
