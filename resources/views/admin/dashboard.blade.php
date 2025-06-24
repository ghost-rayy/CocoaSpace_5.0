@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')

<div class="container">
    <h1 class="font-weight: bolder;">List of all Users</h1>

    <button id="openModalBtn" class="btn btn-success" style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px; cursor: hand;">Add New User</button>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: true,
            confirmButtonText: 'Done',
            confirmButtonColor: '#42CCC5',
            background: '#fff',
            iconColor: '#42CCC5',
        });
    </script>
    @endif

    
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Staff ID</th>
                        <th>Department</th>
                        <th>Office Number</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->staff_id }}</td>
                            <td>{{ $user->department }}</td>
                            <td>{{ $user->office_number }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-user-btn" 
                                        data-id="{{ $user->id }}" 
                                        data-name="{{ $user->name }}" 
                                        data-email="{{ $user->email }}" 
                                        data-role="{{ $user->role }}"
                                        data-staff-id="{{ $user->staff_id }}" 
                                        data-department="{{ $user->department }}" 
                                        data-office-number="{{ $user->office_number }}"
                                        style="padding:5px; background-color:#42CCC5; text-decoration:none; color:white; border:none; margin-right:5px;">Edit</button>
                                <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}" style="padding:5px; background-color:#42CCC5; text-decoration:none; color:white; border:none;">Delete</button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users-destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const deleteButtons = document.querySelectorAll('.delete-user');
                                        deleteButtons.forEach(button => {
                                            button.addEventListener('click', function () {
                                                const userId = this.getAttribute('data-id');
                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: "This action cannot be undone.",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Yes, delete it!'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('delete-form-' + userId).submit();
                                                    }
                                                });
                                            });
                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal structure for Add New User -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New User</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users-store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="staff" selected>Staff</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Staff ID</label>
            <input type="text" name="staff_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department" class="form-control" required>
                <option value="" disabled selected>Select Department</option>
                <option value="A&QC">A&QC</option>
                <option value="Audit">Audit</option>
                <option value="Bills">Bills</option>
                <option value="Board Room">Board Room</option>
                <option value="Cash Office">Cash Office</option>
                <option value="CE">CE</option>
                <option value="Civil Works">Civil Works</option>
                <option value="DCE">DCE</option>
                <option value="Estate">Estate</option>
                <option value="Executive Lounge">Executive Lounge</option>
                <option value="F&A">F&A</option>
                <option value="Finance">Finance</option>
                <option value="Galamseys">Galamseys</option>
                <option value="General Services">General Services</option>
                <option value="Human Resource">Human Resource</option>
                <option value="Information Technology Department">Information Technology Department</option>
                <option value="Intelligence Unit">Intelligence Unit</option>
                <option value="Investment">Investment</option>
                <option value="Legal">Legal</option>
                <option value="Library">Library</option>
                <option value="OPs">OPs</option>
                <option value="Procurement">Procurement</option>
                <option value="Procurement Annex">Procurement Annex</option>
                <option value="Produce Finance">Produce Finance</option>
                <option value="Public Affairs">Public Affairs</option>
                <option value="Public Affairs Annex">Public Affairs Annex</option>
                <option value="Radio">Radio</option>
                <option value="Reconciliation">Reconciliation</option>
                <option value="Research">Research</option>
                <option value="Salaries/Payroll">Salaries/Payroll</option>
                <option value="Shea Unit">Shea Unit</option>
                <option value="Special Services">Special Services</option>
                <option value="Stores">Stores</option>
                <option value="Tax">Tax</option>
                <option value="Telephone Exchange">Telephone Exchange</option>
                <option value="Transport">Transport</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Office Number</label>
            <input type="text" name="office_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
            <button type="submit" class="btn btn-primary" style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px; cursor: hand;">Add User</button>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit User</h2>

        <form action="{{ route('admin.users-update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" id="edit_user_id">

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" id="edit_role" class="form-control" required>
                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Staff ID</label>
                <input type="text" name="staff_id" id="edit_staff_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department" id="edit_department" class="form-control" required>
                    <option value="" disabled>Select Department</option>
                    <option value="A&QC">A&QC</option>
                    <option value="Audit">Audit</option>
                    <option value="Bills">Bills</option>
                    <option value="Board Room">Board Room</option>
                    <option value="Cash Office">Cash Office</option>
                    <option value="CE">CE</option>
                    <option value="Civil Works">Civil Works</option>
                    <option value="DCE">DCE</option>
                    <option value="Estate">Estate</option>
                    <option value="Executive Lounge">Executive Lounge</option>
                    <option value="F&A">F&A</option>
                    <option value="Finance">Finance</option>
                    <option value="Galamseys">Galamseys</option>
                    <option value="General Services">General Services</option>
                    <option value="Human Resource">Human Resource</option>
                    <option value="Information Technology Department">Information Technology Department</option>
                    <option value="Intelligence Unit">Intelligence Unit</option>
                    <option value="Investment">Investment</option>
                    <option value="Legal">Legal</option>
                    <option value="Library">Library</option>
                    <option value="OPs">OPs</option>
                    <option value="Procurement">Procurement</option>
                    <option value="Procurement Annex">Procurement Annex</option>
                    <option value="Produce Finance">Produce Finance</option>
                    <option value="Public Affairs">Public Affairs</option>
                    <option value="Public Affairs Annex">Public Affairs Annex</option>
                    <option value="Radio">Radio</option>
                    <option value="Reconciliation">Reconciliation</option>
                    <option value="Research">Research</option>
                    <option value="Salaries/Payroll">Salaries/Payroll</option>
                    <option value="Shea Unit">Shea Unit</option>
                    <option value="Special Services">Special Services</option>
                    <option value="Stores">Stores</option>
                    <option value="Tax">Tax</option>
                    <option value="Telephone Exchange">Telephone Exchange</option>
                    <option value="Transport">Transport</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Office Number</label>
                <input type="text" name="office_number" id="edit_office_number" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px; cursor: hand;">Update User</button>
        </form>
    </div>
</div>
@endsection

<style>
    h1 {
        text-align: center;
        font-size: 25px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: turquoise;
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
        text-align: left;
        padding: 12px;
        z-index: 2;
    }

    .fl-table th, .fl-table td {
        padding: 10px;
        text-align: left;
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
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table-responsive table {
            min-width: 600px;
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
        max-width: 30%;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: slideDown 0.4s ease forwards;
        position: relative;
    }

    @media (max-width: 600px) {
        .modal-content {
            width: 90% !important;
            max-width: 99% !important;
            padding: 8px 7px !important;
        }
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
    .modal-content input[type="email"],
    .modal-content input[type="password"],
    .modal-content select {
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
    .modal-content input[type="email"]:focus,
    .modal-content input[type="password"]:focus,
    .modal-content select:focus {
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
        // Existing modal code
        var modal = document.getElementById("addUserModal");
        var btn = document.getElementById("openModalBtn");
        var span = document.getElementsByClassName("close")[0];

        // Edit modal elements
        var editModal = document.getElementById("editUserModal");
        var editButtons = document.querySelectorAll('.edit-user-btn');
        var editClose = editModal.querySelector('.close');

        // Edit button click handler
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                const staffId = this.getAttribute('data-staff-id');
                const department = this.getAttribute('data-department');
                const officeNumber = this.getAttribute('data-office-number');
                const role = this.getAttribute('data-role');

                // Set form values
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_staff_id').value = staffId;
                document.getElementById('edit_department').value = department;
                document.getElementById('edit_office_number').value = officeNumber;
                document.getElementById('edit_role').value = role;

                editModal.style.display = "block";
            });
        });

        // Close edit modal
        editClose.onclick = function() {
            editModal.style.display = "none";
        }

        // Existing modal handlers
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
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }
    });

    // Example: when opening the modal
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var role = button.data('role'); // e.g., data-role="admin"
        var modal = $(this);
        modal.find('select[name="role"]').val(role);
    });
</script>
</create_file>
