@extends('layouts.admin-sidebar')

@section('content')
<div style="padding: 24px; background: #ffffff; min-height: 81vh; margin-top:-20px">
    @php
        $bookedRooms = $rooms->filter(function($room) {
            return $room->bookings->count() > 0;
        })->count();

        $availableRooms = $rooms->count() - $bookedRooms;
    @endphp
    <div style="display: flex; gap: 24px; align-items: flex-start;">
        <!-- Left Column: Stats and Chart -->
        <div style="flex: 2; display: flex; flex-direction: column; gap: 24px;">
            <!-- Top Row: Stats -->
            <div style="display: flex; gap: 24px; height:180px;">
                <!-- Traffic by Location -->
                <div style="background: #E3F5FF; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.08); display: flex; flex-direction: column; justify-content: center; height: 100%; width: 100%; min-width: 340px; max-width: 520px;">
                    <div style="font-size: 15px; color: #333; font-weight: 600; margin-bottom: 12px;">Room Status</div>
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <!-- Available Rooms Bar -->
                        <div style="display: flex; align-items: center; gap: 18px;">
                            <span style="width: 120px; font-size: 15px; color: #333;">Available rooms</span>
                            <div style="flex: 1; background: #ffffff; border-radius: 0px; height: 18px; position: relative; overflow: hidden;">
                                <div style="background:rgba(73, 226, 219, 0.786); height: 100%; width: {{ ($availableRooms / ($availableRooms + $bookedRooms)) * 100 }}%; border-radius: 0px; display: flex; align-items: center;">
                                    <span style="color: #fff; font-weight: 600; font-size: 10px; padding-left: 5px; text-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                                        {{ round($availableRooms / ($availableRooms + $bookedRooms) * 100, 1) }}% ({{ $availableRooms }})
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Booked Rooms Bar -->
                        <div style="display: flex; align-items: center; gap: 18px;">
                            <span style="width: 120px; font-size: 15px; color: #333;">Booked rooms</span>
                            <div style="flex: 1; background: #ffffff; border-radius: 0px; height: 18px; position: relative; overflow: hidden;">
                                <div style="background: #ff0303; height: 100%; width: {{ ($bookedRooms / ($availableRooms + $bookedRooms)) * 100 }}%; border-radius: 0px; display: flex; align-items: center;">
                                    <span style="color: #fff; font-weight: 600; font-size: 10px; padding-left:5px; text-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                                        {{ round($bookedRooms / ($availableRooms + $bookedRooms) * 100, 1) }}% ({{ $bookedRooms }})
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="font-size: 13px; color: #6c757d; margin-top: 18px; text-align: right;">Total rooms: {{ $availableRooms + $bookedRooms }}</div>
                </div>
                <!-- Pending Bookings -->
                <div style="flex: 1; background: #42CCC5; border-radius: 12px; padding: 20px; color: white; box-shadow: 0 2px 8px rgb(0 0 0 / 0.08); display: flex; flex-direction: column; justify-content: center; align-items: flex-start; min-width: 200px;">
                    <div style="font-weight: 700; margin-bottom: 5px; font-size: 18px;">Pending Bookings</div>
                    <div style="font-size: 32px; font-weight: 700;">{{ $pendingBookings }}</div>
                    <a href="{{ route('admin.requests') }}" style="background: white; color: #42CCC5; padding: 6px 18px; border-radius: 6px; font-weight: 600; font-size: 15px; text-decoration: none; margin-top: 10px;">View</a>
                </div>
                <!-- Total Users -->
                <div style="flex: 1; background: #E3F5FF; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.08); min-width: 180px; display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">
                    <div style="font-weight: 700; font-size: 18px; margin-bottom: 30px;">Total Number of Users</div>
                    <div style="font-size: 28px; font-weight: 700;">{{ $totalUsers }}</div>
                </div>
                <!-- Total Bookings -->
                <div style="flex: 1; background: #42CCC5; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.08); min-width: 180px; display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">
                    <div style="font-weight: 700; font-size: 19px; margin-bottom: 30px; color:#fff;">Total Number of Bookings</div>
                    <div style="font-size: 28px; font-weight: 700; color:#fff;">{{ $totalBookings }}</div>
                </div>
            </div>
        </div>
        <!-- Right Column: Book a Meeting Room Card with Modal Trigger -->
        <div style="flex: 1; background: #E3F5FF; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.08); min-width: 240px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 180px;">
            <div style="font-weight: 700; font-size: 20px; text-align: left; margin-bottom: 18px;">Book Meeting Room</div>
            <button id="openBookModalBtn" type="button" class="btn btn-primary" style="padding: 10px 32px; font-size: 16px; border-radius: 6px; font-weight: 600;">Book Now</button>
        </div>
    </div>
    
    <!-- Bookings per Period -->
    <div style="margin-top:30px; margin-left:0px; height: 400px; background: #f0f4f8; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1); width: 1230px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 style="font-weight: 600;">Bookings per {{ ucfirst($period) }}</h6>
            <form id="periodForm" method="GET" action="{{ route('admin.home') }}">
                <select name="period" id="periodSelect" class="form-select" style="width: 150px; color:#36b3ad;">
                    <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Year</option>
                </select>
            </form>
        </div>
        <canvas id="bookingsChart" width="480px" height="150px"></canvas>
    </div>
</div>

<!-- Custom Modal Structure -->
<div id="bookMeetingModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeBookModalBtn">&times;</span>
        <h2>Book a Meeting Room</h2>
        @include('admin.partials.booking_form', ['rooms' => $rooms])
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById("bookMeetingModal");
        var btn = document.getElementById("openBookModalBtn");
        var span = document.getElementById("closeBookModalBtn");

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

    // Bookings per Period Bar Chart
    const ctxBookings = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(ctxBookings, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(66, 204, 197, 0.7)',
                borderWidth: 0
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Period selector change handler
    document.getElementById('periodSelect').addEventListener('change', function() {
        document.getElementById('periodForm').submit();
    });
</script>

<style>
    /* body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffffff;
        color: #333333;
    } */
    h4, h5, h6 {
        font-weight: 600;
        color: #333333;
    }
    h6 {
        font-size: 14px;
    }
    h4 {
        font-size: 18px;
    }
    h2 {
        font-size: 28px;
        margin: 0;
        font-weight: 700;
        color: #333333;
    }
    .btn-primary {
        background-color: #42CCC5;
        border-color: #42CCC5;
        font-weight: 600;
        font-size: 16px;
        border-radius: 6px;
        padding: 8px 0;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #36b3ad;
        border-color: #36b3ad;
    }
    input.form-control, select.form-select, textarea.form-control {
        font-size: 14px;
        padding: 4px 8px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease;
    }
    textarea.form-control {
        height: auto;
        padding-top: 6px;
        padding-bottom: 6px;
    }
    input.form-control:focus, select.form-select:focus, textarea.form-control:focus {
        border-color: #42CCC5;
        outline: none;
        box-shadow: 0 0 5px rgba(66, 204, 197, 0.5);
    }
    .bg-light {
        background-color: #f0f4f8 !important;
    }
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
    }
    .modal-backdrop.show {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
    }
    .custom-modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(66,204,197,0.10), 0 1.5px 6px rgba(0,0,0,0.08);
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border: none;
        transition: opacity 0.3s ease;
    }
    .custom-modal-header {
        border-bottom: 1px solid #e3f5ff;
        padding: 20px 28px 12px 28px;
        align-items: center;
    }
    .custom-modal-title {
        margin-bottom: 0;
        font-weight: 700;
        color: #393939;
        text-align: center;
        font-size: 24px;
        width: 100%;
    }
    .custom-modal-body {
        padding: 28px 28px 20px 28px;
        background: #fcfeff;
        width: 100%;
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

    .custom-modal-close {
        filter: invert(60%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(90%) contrast(90%);
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .custom-modal-close:hover {
        opacity: 1 !important;
    }

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

    /* Responsive styles */
    @media (max-width: 1230px) {
        .chart-container {
            width: 100% !important;
        }
    }
    @media (max-width: 900px) {
        div[style*='display: flex; gap: 24px; align-items: flex-start;'] {
            flex-direction: column !important;
            gap: 16px !important;
        }
        div[style*='flex: 2; display: flex; flex-direction: column; gap: 24px;'] {
            width: 100% !important;
        }
        div[style*='display: flex; gap: 24px; height:180px;'] {
            flex-direction: column !important;
            height: auto !important;
            gap: 16px !important;
        }
        div[style*='min-width: 340px; max-width: 520px;'] {
            min-width: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        div[style*='min-width: 200px;'] {
            min-width: 0 !important;
            width: 100% !important;
        }
        div[style*='min-width: 180px;'] {
            min-width: 0 !important;
            width: 100% !important;
        }
        div[style*='min-width: 240px;'] {
            min-width: 0 !important;
            width: 100% !important;
            margin-top: 12px !important;
        }
        .modal-content {
            width: 95% !important;
            padding: 18px 8px !important;
        }
        .chart-container, #bookingsChart {
            width: 100% !important;
            min-width: 0 !important;
        }
    }
    @media (max-width: 600px) {
        div[style*='padding: 24px; background: #ffffff; min-height: 81vh; margin-top:-20px'] {
            padding: 8px !important;
        }
        div[style*='background: #E3F5FF; border-radius: 12px; padding: 20px;'] {
            padding: 10px !important;
        }
        div[style*='background: #42CCC5; border-radius: 12px; padding: 20px;'] {
            padding: 10px !important;
        }
        div[style*='background: #E3F5FF; border-radius: 12px; padding: 24px;'] {
            padding: 10px !important;
        }
        .modal-content {
            width: 99% !important;
            padding: 8px 2px !important;
        }
        h2, .modal-content h2 {
            font-size: 18px !important;
        }
        .btn-primary {
            font-size: 14px !important;
            padding: 8px 0 !important;
        }
        input.form-control, select.form-select, textarea.form-control {
            font-size: 12px !important;
            padding: 2px 4px !important;
            height: 28px !important;
        }
        .modal-content button[type="submit"] {
            font-size: 14px !important;
            padding: 8px 0 !important;
        }
        #bookingsChart {
            height: 120px !important;
        }
    }
</style>
@endsection
