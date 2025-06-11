@extends('layouts.admin-sidebar')

@section('content')
<div class="container">
<!-- Traffic by Location -->
<div style="flex: 1; background: #F9F9FA; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1); width: 22%; height:170px;">
    <canvas id="trafficChart" width="30px  " height="40px"></canvas>
    <div class="d-flex align-items-center mt-3" style="font-size: 12px; color: #6c757d;">
        <div class="d-flex align-items-center me-3" style="margin-top: -100px; margin-left:170px">
            <div style="width: 12px; height: 12px; background-color: #28a745; border-radius: 50%;"></div>
            <span class="ms-2">Available rooms</span>
        </div>
        <div class="d-flex align-items-center" style="margin-left:170px">
            <div style="width: 12px; height: 12px; background-color: #dc3545; border-radius: 50%;"></div>
            <span class="ms-2">Booked Rooms</span>
        </div>
    </div>
</div>

<!-- Pending Bookings -->
<div style="margin-left:330px; margin-top:-210px; flex: 1; background: #42CCC5; border-radius: 12px; padding: 20px; color: white; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1); width: 23.7%; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h6 style="font-weight: 600; margin-bottom: 5px;">Pending Bookings</h6>
        <h2 style="margin: 0;">{{ $pendingBookings }}</h2>
    </div>
    <a href="{{ route('admin.requests') }}" style="background: white; color: #42CCC5; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none;">View</a>
</div>
<div class="d-flex gap-3 mb-3" style="flex-wrap: nowrap; margin-left:330px; margin-top:10px;">
<!-- Total Users -->
<div style=" flex: 1; background: #E3F5FF; border-radius: 12px; padding: 15px 20px; width: 125px;">
    <h6 style="font-weight: 600; margin-bottom: 5px; font-size: 14px;">Total Number of Users</h6>
    <h4 style="margin: 0;">{{ $totalUsers }}</h4>
</div>
<!-- Total Bookings -->
<div style="margin-left:170px; margin-top:-103px; flex: 1; background: #E3F5FF; border-radius: 12px; padding: 15px 20px; width: 125px;">
    <h6 style="font-weight: 600; margin-bottom: 5px; font-size: 14px;">Total Number of Bookings</h6>
    <h4 style="margin: 0;">{{ $totalBookings }}</h4>
</div>
<!-- Bookings per Period -->
<div style="margin-top:20px; margin-left:-330px; height: 300px; background: #f0f4f8; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1); width: 615px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 style="font-weight: 600;">Bookings per {{ ucfirst($period) }}</h6>
        <form id="periodForm" method="GET" action="{{ route('admin.home') }}">
            <select name="period" id="periodSelect" class="form-select" style="width: 150px;">
                <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Day</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Week</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Month</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Year</option>
            </select>
        </form>
    </div>
    <canvas id="bookingsChart" width="400px" height="150px"></canvas>
</div>
    <!-- Right side: Booking form partial -->
<div style="background-color:#F9F9FA; flex: 1; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1); width: 500px; height:548px; margin-left:346px; margin-top:-585px;">
    @include('admin.partials.booking_form', ['rooms' => $rooms])
</div>
</div>



<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Traffic by Location Donut Chart
    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
    const trafficChart = new Chart(ctxTraffic, {
        type: 'doughnut',
        data: {
            labels: ['Available rooms', 'Booked Rooms'],
            datasets: [{
                data: [{{ $availableRooms }}, {{ $bookedRooms }}],
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '50%',
            plugins: {
                legend: {
                    display: false
                }
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
</style>
@endsection
