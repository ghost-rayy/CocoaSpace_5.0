@extends('layouts.admin-sidebar')

@section('content')
<div class="container">
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

        <button type="submit" class="btn btn-primary">Add User</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var userForm = document.querySelector('form[action="{{ route('admin.users-store') }}"]');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            Swal.fire({
                title: 'Adding user...',
                text: 'Please wait while we add the user.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    }
});
</script>
@endsection
