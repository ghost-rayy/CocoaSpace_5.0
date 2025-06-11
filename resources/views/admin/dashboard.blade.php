@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')

<div class="container">
    <h1>List of all Users</h1>

    <!-- Button to open the modal -->
    <button id="openModalBtn" class="btn btn-success" style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px; cursor: hand; margin-top:-100px; margin-bottom:-150px;">Add New User</button>

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
                        <td>{{ $user->is_admin ? 'Admin' : 'Staff' }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">Delete</button>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users-destroy', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <!-- Modal -->
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form action="{{ route('admin.users-update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name{{ $user->id }}" class="form-label">Name</label>
                                            <input type="text" id="name{{ $user->id }}" name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email{{ $user->id }}" class="form-label">Email</label>
                                            <input type="email" id="email{{ $user->id }}" name="email" class="form-control" value="{{ $user->email }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="staff_id{{ $user->id }}" class="form-label">Staff ID</label>
                                            <input type="text" id="staff_id{{ $user->id }}" name="staff_id" class="form-control" value="{{ $user->staff_id }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="department{{ $user->id }}" class="form-label">Department</label>
                                            <select id="department{{ $user->id }}" name="department" class="form-control" required>
                                                <option value="" disabled>Select Department</option>
                                                <option value="A&QC" {{ $user->department == 'A&QC' ? 'selected' : '' }}>A&QC</option>
                                                <option value="Audit" {{ $user->department == 'Audit' ? 'selected' : '' }}>Audit</option>
                                                <option value="Bills" {{ $user->department == 'Bills' ? 'selected' : '' }}>Bills</option>
                                                <option value="Board Room" {{ $user->department == 'Board Room' ? 'selected' : '' }}>Board Room</option>
                                                <option value="Cash Office" {{ $user->department == 'Cash Office' ? 'selected' : '' }}>Cash Office</option>
                                                <option value="CE" {{ $user->department == 'CE' ? 'selected' : '' }}>CE</option>
                                                <option value="Civil Works" {{ $user->department == 'Civil Works' ? 'selected' : '' }}>Civil Works</option>
                                                <option value="DCE" {{ $user->department == 'DCE' ? 'selected' : '' }}>DCE</option>
                                                <option value="Estate" {{ $user->department == 'Estate' ? 'selected' : '' }}>Estate</option>
                                                <option value="Executive Lounge" {{ $user->department == 'Executive Lounge' ? 'selected' : '' }}>Executive Lounge</option>
                                                <option value="F&A" {{ $user->department == 'F&A' ? 'selected' : '' }}>F&A</option>
                                                <option value="Finance" {{ $user->department == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                <option value="Galamseys" {{ $user->department == 'Galamseys' ? 'selected' : '' }}>Galamseys</option>
                                                <option value="General Services" {{ $user->department == 'General Services' ? 'selected' : '' }}>General Services</option>
                                                <option value="Human Resource" {{ $user->department == 'Human Resource' ? 'selected' : '' }}>Human Resource</option>
                                                <option value="Information Technology Department" {{ $user->department == 'Information Technology Department' ? 'selected' : '' }}>Information Technology Department</option>
                                                <option value="Intelligence Unit" {{ $user->department == 'Intelligence Unit' ? 'selected' : '' }}>Intelligence Unit</option>
                                                <option value="Investment" {{ $user->department == 'Investment' ? 'selected' : '' }}>Investment</option>
                                                <option value="Legal" {{ $user->department == 'Legal' ? 'selected' : '' }}>Legal</option>
                                                <option value="Library" {{ $user->department == 'Library' ? 'selected' : '' }}>Library</option>
                                                <option value="OPs" {{ $user->department == 'OPs' ? 'selected' : '' }}>OPs</option>
                                                <option value="Procurement" {{ $user->department == 'Procurement' ? 'selected' : '' }}>Procurement</option>
                                                <option value="Procurement Annex" {{ $user->department == 'Procurement Annex' ? 'selected' : '' }}>Procurement Annex</option>
                                                <option value="Produce Finance" {{ $user->department == 'Produce Finance' ? 'selected' : '' }}>Produce Finance</option>
                                                <option value="Public Affairs" {{ $user->department == 'Public Affairs' ? 'selected' : '' }}>Public Affairs</option>
                                                <option value="Public Affairs Annex" {{ $user->department == 'Public Affairs Annex' ? 'selected' : '' }}>Public Affairs Annex</option>
                                                <option value="Radio" {{ $user->department == 'Radio' ? 'selected' : '' }}>Radio</option>
                                                <option value="Reconciliation" {{ $user->department == 'Reconciliation' ? 'selected' : '' }}>Reconciliation</option>
                                                <option value="Research" {{ $user->department == 'Research' ? 'selected' : '' }}>Research</option>
                                                <option value="Salaries/Payroll" {{ $user->department == 'Salaries/Payroll' ? 'selected' : '' }}>Salaries/Payroll</option>
                                                <option value="Shea Unit" {{ $user->department == 'Shea Unit' ? 'selected' : '' }}>Shea Unit</option>
                                                <option value="Special Services" {{ $user->department == 'Special Services' ? 'selected' : '' }}>Special Services</option>
                                                <option value="Stores" {{ $user->department == 'Stores' ? 'selected' : '' }}>Stores</option>
                                                <option value="Tax" {{ $user->department == 'Tax' ? 'selected' : '' }}>Tax</option>
                                                <option value="Telephone Exchange" {{ $user->department == 'Telephone Exchange' ? 'selected' : '' }}>Telephone Exchange</option>
                                                <option value="Transport" {{ $user->department == 'Transport' ? 'selected' : '' }}>Transport</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="office_number{{ $user->id }}" class="form-label">Office Number</label>
                                            <input type="text" id="office_number{{ $user->id }}" name="office_number" class="form-control" value="{{ $user->office_number }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-success">Update User</button>
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
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

@endsection

<style>
    h1 {
        text-align: center;
        font-size: 18px;
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

        var modal = document.getElementById("addUserModal");

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
</script>
</create_file>
