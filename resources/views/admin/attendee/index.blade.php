@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')

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

<div class="container">
    <h1>Register Attendees</h1>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 0px;">
        <form action="{{ route('admin.registration') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or e-ticket" style="padding: 8px 12px; border: 1px solid #42CCC5; border-radius: 5px;">
            <button type="submit"
                    style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px;">
                🔍 Search
            </button>
            <a href="{{ route('attendees.import.form') }}" class="btns" style="margin-left: 10px; display: flex; align-items: center; background: #0f766e; border-radius:5px;">
                Import Attendees
            </a>
        </form>
    </div>

    <div class="table-responsive"><div class="table-wrapper" style="overflow-x:auto;">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Requester</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Meeting ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->requester }}</td>
                        <td>{{ $booking->date }}</td>
                        <td>{{ $booking->time }}</td>
                        <td>
                            {{ $booking->meetingRoom->name ?? 'N/A' }}<br>
                            {{-- Room No: {{ $booking->meetingRoom->room_number ?? '-' }}<br>
                            Floor: {{ $booking->meetingRoom->floor ?? '-' }} --}}
                        </td>
                        <td>
                            @if($booking->e_ticket)
                                <span id="ticket-{{ $booking->id }}" style="color: green; font-weight: bold;">{{ $booking->e_ticket }}</span>
                                {{-- <button onclick="copyToClipboard({{ $booking->id }})" class="btn btn-sm btn-outline-secondary">Copy</button> --}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">Approved</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.attendees.register', $booking->id) }}" class="btns">Load</a>
                            <a href="{{ route('admin.attendees.view', $booking->id) }}" class="btns">View Attendance</a>
                            {{-- <a href='#' class="btns view-documents-btn" data-booking-id="{{ $booking->id }}">View Documents</a> --}}
                            <form action="{{ route('admin.uploadFlyer', $booking->id) }}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <input type="file" name="flyer" accept="image/*" required id="flyer-{{ $booking->id }}" class="file-input">
                                <label for="flyer-{{ $booking->id }}" class="btns" style="margin-top:10px; cursor:hand;">Upload Flyer</label>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: red; font-weight: bold;">
                            No approved bookings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection

<script>
    function copyToClipboard(id) {
        const text = document.getElementById("ticket-" + id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert("E-Ticket copied!");
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.file-input').forEach(function(input) {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
});
</script>

<style>
    h1 {
        text-align: center;
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        padding: 5px 0;
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
        text-transform: uppercase;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f4faff;
    }

    .fl-table tbody tr:hover {
        background-color: #e0f0ff;
    }

    .btns:hover{
        color: #fcfcfc;
        background-color: #1f6d69;
    }

    .btns{
        text-decoration: none; 
        background-color:#42CCC5; 
        padding:5px; 
        color:white;
        border-radius: 5px;
        align-items: center;
        gap: 5px;
    }

    .upload-flyer-form {
        display: flex;
        gap: 5px;
        align-items: center;
        margin: 0;
        background-color: #1f6d69;
        border-radius: 8px;
    }

    .file-input {
        display: none;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table-responsive table {
            min-width: 600px;
        }
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Open modal
  document.querySelectorAll('.open-bulk-modal').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('bulkRegisterModal').style.display = 'flex';
      document.getElementById('bulk_booking_id').value = this.getAttribute('data-booking-id');
    });
  });
  // Close modal
  document.getElementById('closeBulkModalBtn').onclick = function() {
    document.getElementById('bulkRegisterModal').style.display = 'none';
  };
  window.onclick = function(event) {
    if (event.target == document.getElementById('bulkRegisterModal')) {
      document.getElementById('bulkRegisterModal').style.display = 'none';
    }
  };
  // Add attendee row
  document.getElementById('addAttendeeBtn').onclick = function() {
    const row = document.createElement('div');
    row.className = 'attendee-row';
    row.style = 'display:flex; gap:8px; margin-bottom:10px;';
    row.innerHTML = `<input type="text" name="names[]" placeholder="Name" class="form-control" required style="flex:1;">
      <input type="email" name="emails[]" placeholder="Email" class="form-control" required style="flex:1;">
      <button type="button" class="remove-attendee" style="background:#ff0303; color:#fff; border:none; border-radius:4px; padding:0 8px;">-</button>`;
    document.getElementById('attendeesFields').appendChild(row);
    row.querySelector('.remove-attendee').onclick = function() {
      row.remove();
    };
  };
  // Remove attendee row
  document.querySelectorAll('.remove-attendee').forEach(function(btn) {
    btn.onclick = function() {
      btn.parentElement.remove();
    };
  });
  // AJAX submit
  document.getElementById('bulkRegisterForm').onsubmit = function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    Swal.fire({
      title: 'Registering...',
      text: 'Attendees are being registered and codes will be sent in the background.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); }
    });
    fetch('/admin/attendees/bulk-register', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: data
    })
    .then(response => response.json())
    .then(res => {
      Swal.close();
      if(res.success) {
        Swal.fire('Success', res.message, 'success');
        document.getElementById('bulkRegisterModal').style.display = 'none';
        form.reset();
        document.getElementById('attendeesFields').innerHTML = `<div class="attendee-row" style="display:flex; gap:8px; margin-bottom:10px;"><input type="text" name="names[]" placeholder="Name" class="form-control" required style="flex:1;"><input type="email" name="emails[]" placeholder="Email" class="form-control" required style="flex:1;"><button type="button" class="remove-attendee" style="background:#ff0303; color:#fff; border:none; border-radius:4px; padding:0 8px;">-</button></div>`;
      } else {
        Swal.fire('Error', res.message || 'Something went wrong.', 'error');
      }
    })
    .catch(() => {
      Swal.close();
      Swal.fire('Error', 'Something went wrong.', 'error');
    });
  };
});
</script>

<!-- Modal for viewing documents -->
<div id="documentsModal" style="display:none; position:fixed; z-index:3000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:420px; margin:auto; position:relative; min-width:320px;">
        <span id="closeDocumentsModal" style="position:absolute; top:12px; right:18px; font-size:28px; cursor:pointer;">&times;</span>
        <h2 style="margin-bottom:18px;">Booking Documents</h2>
        <div id="documentsList">
            <!-- Documents will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // View Documents modal logic
    var modal = document.getElementById('documentsModal');
    var closeBtn = document.getElementById('closeDocumentsModal');
    var documentsList = document.getElementById('documentsList');
    document.querySelectorAll('.view-documents-btn').forEach(function(btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            var bookingId = btn.getAttribute('data-booking-id');
            fetch('/booking/' + bookingId + '/documents')
                .then(response => response.json())
                .then(docs => {
                    if (docs.length === 0) {
                        documentsList.innerHTML = '<p style="color:red;">No documents found for this booking.</p>';
                    } else {
                        documentsList.innerHTML = docs.map(function(doc) {
                            return '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">' +
                                '<a href="/storage/' + doc.file_path.replace('booking_documents/', 'booking_documents/') + '" target="_blank" style="color:#42CCC5; font-weight:600;">' + doc.original_name + '</a>' +
                                '<span class="delete-doc-btn" data-doc-id="' + doc.id + '" style="cursor:pointer;margin-left:12px;color:#fff;background:#e3342f;border-radius:50%;padding:7px 10px;display:inline-block;">' +
                                    '<i class="fa fa-trash"></i>' +
                                '</span>' +
                            '</div>';
                        }).join('');
                    }
                    modal.style.display = 'flex';
                });
        };
    });
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    };
    // Delete document logic
    documentsList.addEventListener('click', function(e) {
        if (e.target.closest('.delete-doc-btn')) {
            var btn = e.target.closest('.delete-doc-btn');
            var docId = btn.getAttribute('data-doc-id');
            if (confirm('Are you sure you want to delete this document?')) {
                fetch('/booking/document/' + docId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.parentElement.remove();
                    } else {
                        alert('Failed to delete document.');
                    }
                });
            }
        }
    });
});
</script>
