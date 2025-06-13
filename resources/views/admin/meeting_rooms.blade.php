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
                        <button class="btn btn-warning edit-room-btn" style="padding:5px; background-color:#42CCC5; text-decoration:none; color:white; border:none;" data-id="{{ $room->id }}" data-name="{{ $room->name }}" data-room_number="{{ $room->room_number }}" data-floor="{{ $room->floor }}" data-capacity="{{ $room->capacity }}">Edit</button>
                        <form action="{{ route('admin.meeting_rooms.destroy', $room->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button style="padding:5px; background-color:#42CCC5; text-decoration:none; color:white; border:none;" type="submit" class="btn btn-danger delete-room-btn">Delete</button>
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

<!-- Modal structure for Edit Meeting Room -->
<div id="editRoomModal" class="modal">
    <div class="modal-content">
        <span class="close" id="editModalClose">&times;</span>
        <h2>Edit Meeting Room</h2>

        <form id="editRoomForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="editName" class="form-label">Room Name</label>
                <input type="text" id="editName" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="editRoomNumber" class="form-label">Room Number</label>
                <input type="text" id="editRoomNumber" name="room_number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="editFloor" class="form-label">Floor</label>
                <input type="text" id="editFloor" name="floor" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="editCapacity" class="form-label">Capacity</label>
                <input type="number" id="editCapacity" name="capacity" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById("editRoomModal");
        var editForm = document.getElementById("editRoomForm");
        var editModalClose = document.getElementById("editModalClose");

        // Open edit modal and populate form fields
        document.querySelectorAll('.edit-room-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var roomId = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var roomNumber = this.getAttribute('data-room_number');
                var floor = this.getAttribute('data-floor');
                var capacity = this.getAttribute('data-capacity');

                editForm.action = '/admin/meeting-rooms/' + roomId + '/update';
                editForm.querySelector('#editName').value = name;
                editForm.querySelector('#editRoomNumber').value = roomNumber;
                editForm.querySelector('#editFloor').value = floor;
                editForm.querySelector('#editCapacity').value = capacity;

                editModal.style.display = "block";
            });
        });

        // Close edit modal
        editModalClose.onclick = function() {
            editModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }
    });
</script>
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
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        transition: opacity 0.3s ease;
    }

    .modal-content {
        background-color: #ffffff;
        margin: 8% auto;
        padding: 30px 40px;
        border-radius: 12px;
        width: 400px;
        max-width: 90%;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: slideDown 0.4s ease forwards;
        position: relative;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .close {
        color: #888;
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        font-weight: 700;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover,
    .close:focus {
        color: #42CCC5;
        text-decoration: none;
    }

    /* Form inside modal */
    .modal-content h2 {
        margin-bottom: 25px;
        font-weight: 700;
        color: #333;
        text-align: center;
        font-size: 24px;
    }

    .modal-content label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .modal-content input[type="text"],
    .modal-content input[type="number"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 20px;
        border: 1.8px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }

    .modal-content input[type="text"]:focus,
    .modal-content input[type="number"]:focus {
        border-color: #42CCC5;
        outline: none;
        box-shadow: 0 0 8px rgba(66, 204, 197, 0.5);
    }

    .modal-content button[type="submit"] {
        width: 100%;
        background-color: #42CCC5;
        color: white;
        padding: 12px 0;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-content button[type="submit"]:hover {
        background-color: #36b3ad;
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
