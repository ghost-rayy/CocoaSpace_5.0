@extends('layouts.admin-sidebar')

@section('content')
<div class="container">
    <h2>Edit User</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users-update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="staff_id" class="form-label">Staff ID</label>
            <input type="text" name="staff_id" class="form-control" value="{{ $user->staff_id }}" required>
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
            <label for="office_number" class="form-label">Office Number</label>
            <input type="text" name="office_number" class="form-control" value="{{ $user->office_number }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update User</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
