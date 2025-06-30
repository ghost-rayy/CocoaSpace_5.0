@extends('layouts.admin-sidebar')

@section('content')
<style>
.import-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(66,204,197,0.10), 0 2px 8px rgba(0,0,0,0.08);
    padding: 32px 24px 24px 24px;
}
.import-title {
    color: #42CCC5;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 18px;
    text-align: center;
}
.import-form-group {
    margin-bottom: 18px;
}
.import-form-group label {
    display: block;
    color: #333;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 8px;
}
.import-form-input {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #42CCC5;
    border-radius: 8px;
    font-size: 1rem;
    background: #f9fefe;
    transition: border-color 0.2s;
}
.import-form-input:focus {
    border-color: #14b8a6;
    outline: none;
    background: #fff;
}
.import-form-submit {
    width: 100%;
    background: linear-gradient(90deg, #42CCC5 0%, #14b8a6 100%);
    color: #fff;
    padding: 12px 0;
    border: none;
    border-radius: 8px;
    font-size: 1.08rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 10px;
}
.import-form-submit:hover { background: #36b3ad; }
.import-csv-preview {
    margin-top: 18px;
    overflow-x: auto;
    max-height: 250px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(66,204,197,0.08);
}
.import-csv-preview table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.97rem;
    background: #f9fefe;
    border-radius: 8px;
    overflow: hidden;
}
.import-csv-preview th, .import-csv-preview td {
    border: 1px solid #e0f7f5;
    padding: 8px 12px;
    text-align: left;
}
.import-csv-preview th {
    background: #42CCC5;
    color: #fff;
    font-weight: 700;
    position: sticky;
    top: 0;
    z-index: 1;
}
.import-csv-preview tr:nth-child(even) td {
    background: #e0f7f5;
}
.import-csv-preview tr:hover td {
    background: #b2f5ea;
    transition: background 0.2s;
}
@media (max-width: 600px) {
    .import-container {
        padding: 18px 6px 12px 6px;
        max-width: 98vw;
    }
    .import-title {
        font-size: 1.1rem;
    }
    .import-form-input {
        font-size: 0.95rem;
        padding: 10px 8px;
    }
    .import-form-submit {
        font-size: 1rem;
        padding: 10px 0;
    }
    .import-csv-preview th, .import-csv-preview td {
        font-size: 0.92rem;
        padding: 6px 6px;
    }
}
</style>
<div class="import-container">
    <h2 class="import-title">Import Attendees from CSV</h2>
    <a href="{{ route('attendees.download.template') }}" class="import-form-submit" style="display:inline-block; width:auto; margin-bottom:18px; background: #0f766e; text-align:center; text-decoration:none; padding: 10px; margin-left: 300px; font-size:12px;">Download Template</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('attendees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="import-form-group">
            <label for="booking_id">Select Meeting/Booking</label>
            <select name="booking_id" id="booking_id" class="import-form-input" required>
                <option value="" disabled selected>Select Meeting</option>
                @foreach($bookings as $booking)
                    <option value="{{ $booking->id }}">{{ $booking->requester }} - {{ $booking->date }} (Room: {{ $booking->meetingRoom->name ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <div class="import-form-group">
            <label for="csv_file">CSV File</label>
            <input type="file" name="csv_file" id="csv_file" class="import-form-input" required>
        </div>
        <div id="csvPreview" class="import-csv-preview"></div>
        <button type="submit" class="import-form-submit">Import</button>
    </form>
</div>
<script>
document.getElementById('csv_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const text = e.target.result;
        const lines = text.split(/\r?\n/).filter(line => line.trim() !== '');
        if (lines.length === 0) {
            document.getElementById('csvPreview').innerHTML = '<p style="color:#888;">No data found in file.</p>';
            return;
        }
        let html = '<table>';
        lines.forEach((line, idx) => {
            const cells = line.split(',');
            html += '<tr>';
            cells.forEach(cell => {
                html += idx === 0
                    ? `<th>${cell.trim()}</th>`
                    : `<td>${cell.trim()}</td>`;
            });
            html += '</tr>';
        });
        html += '</table>';
        document.getElementById('csvPreview').innerHTML = html;
    };
    reader.readAsText(file);
});
</script>
@endsection
