@extends('layouts.user-sidebar')

@if($bookings->isEmpty())
    <p>No bookings found.</p>
@else
<div class="table-wrapper">
    <table class="fl-table">
        <thead>
            <tr>
                <th>Duration</th>
                <th>Date</th>
                <th>Time</th>
                <th>Extension</th>
                <th>Reason</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>E-Ticket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->duration }}</td>
                    <td>{{ $booking->date }}</td>
                    <td>{{ $booking->time }}</td>
                    <td>{{ $booking->extension }}</td>
                    <td>{{ $booking->reason }}</td>
                    <td>{{ $booking->capacity }}</td>
                    <td>
                        <span style="color: green; font-weight: bold;">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td>
                        @if($booking->e_ticket)
                            <span class="badge bg-primary">{{ $booking->e_ticket }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<style>
    /* General Styles */
    * {
        box-sizing: border-box;
    }
    /* body {
        font-family: Helvetica, sans-serif;
        background: rgba(71, 147, 227, 1);
    } */

    h1 {
        text-align: center;
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        padding: 5px 0;
    }

    .table-wrapper {
        width: 1500px;
        max-height: 650px;
        overflow-y: auto;
        overflow-x: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0px 35px 50px rgba(0, 0, 0, 0.2);
    }

    .fl-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }
    .fl-table th, .fl-table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }
    .fl-table thead th {
        position: sticky;
        top: 0;
        background: #42CCC5;
        color: white;
        z-index: 2;
    }
    .fl-table thead th:nth-child(odd) {
        background: #324960;
    }
    .fl-table tr:nth-child(even) {
        background: #F8F8F8;
    }

    /* Responsive Fixes */
    @media (max-width: 767px) {
        .table-wrapper {
            max-height: 300px; /* Adjusts height for mobile */
        }
    }
    </style>
