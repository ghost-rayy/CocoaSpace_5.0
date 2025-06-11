@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="container">
    <h1>Meeting Rooms</h1>
    <!-- Button to open the modal -->
    <button id="openModalBtn" class="btn btn-success" style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px; cursor: hand;">Add Meeting Room</button>

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

    <br><br>
    <div class="table-wrapper">
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
                            <button type="submit" class="btn btn-danger delete-room-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal structure for Add Meeting Room -->
<div id="addRoomModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add Meeting Room</h2>

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
</div>
@endsection

<style>
    h1 {
        text-align: center;
        font-size: 28px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: turquoise;
        padding: 30px 0;
        font-weight: bolder;
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

    @media (max-width: 768px) {
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.7);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 400px;
        max-width: 30%;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        var modal = document.getElementById("addRoomModal");

        var btn = document.getElementById("openModalBtn");

        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

    // SweetAlert confirmation for delete buttons
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-room-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the meeting room.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
