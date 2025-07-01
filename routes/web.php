<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingAttendeeController;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AttendeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.homepage');
})->name('homepage');

Route::get('/session-expired', function () {
    return view('session-expired');
})->name('session.expired');

// Route::get('/homepage', function () {
//     return view('auth.homepage');
// });

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
});
// Auth-protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/staff/dashboard', [UserController::class, 'sdash'])->name('dashboard');
    Route::get('/staff/create/booking', [UserController::class, 'createBooking'])->name('booking.create');
    Route::get('/bookings/checkAvailability', [UserController::class, 'checkAvailability'])->name('bookings.checkAvailability');
    Route::post('/bookings/store', [UserController::class, 'store'])->name('bookings.store');
    Route::get('/user/bookings', [UserController::class, 'booking'])->name('user.booking');
    Route::get('/user/refresh-bookings', [App\Http\Controllers\UserController::class, 'refreshBookings'])->name('user.refreshBookings');

    Route::get('/admin/home', [AdminController::class, 'home'])->name('admin.home');
    Route::get('/admin/dashboard', [AdminController::class, 'adash'])->name('admin.dashboard');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users-create');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users-store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users-edit');
    Route::put('/admin/users/update', [AdminController::class, 'update'])->name('admin.users-update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users-destroy');
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/admin/bookings/{id}/approve', [AdminController::class, 'approve'])->name('bookings.approve');
    Route::post('/admin/bookings/{id}/decline', [AdminController::class, 'decline'])->name('bookings.decline');
    Route::post('/admin/bookings/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.bookings.updateStatus');
    Route::get('/requests', [AdminController::class, 'request'])->name('admin.requests');
    Route::post('/admin/meeting-room/store', [AdminController::class, 'storeRoom'])->name('admin.storeRoom');
    Route::get('/room/create', [AdminController::class, 'createRoom'])->name('admin.create');
    Route::get('/admin/room', [AdminController::class, 'index'])->name('admin.room');
    Route::get('/admin/meeting-rooms/{rooms}/edit', [AdminController::class, 'editRoom'])->name('admin.meeting_rooms.edit');
    Route::delete('/admin/meeting-rooms/{rooms}', [AdminController::class, 'destroyRoom'])->name('admin.meeting_rooms.destroy');
    Route::put('/admin/meeting-rooms/{rooms}/update', [AdminController::class, 'updateRoom'])->name('admin.meeting_rooms.update');
    Route::get('/admin/refresh-pending-table', [AdminController::class, 'refreshPendingTable'])->name('admin.refreshPendingTable');
    Route::get('/admin/add-meeting', [AdminController::class, 'add'])->name('admin.add');
    Route::post('/admin/add-meeting', [AdminController::class, 'addStore'])->name('admin.add-store');
    Route::get('/admin/my-bookings', [AdminController::class, 'myBookings'])->name('admin.my-bookings');
    Route::post('/admin/bookings/{id}/end-meeting', [AdminController::class, 'endMeeting'])->name('admin.bookings.endMeeting');
    Route::get('/admin/bookings/history', [AdminController::class, 'bookingHistory'])->name('admin.bookings.history');
    Route::get('/add-booking/checkAvailability', [AdminController::class, 'checkAvailability'])->name('bookings.checkAvailability');
    Route::get('/attendees', [MeetingAttendeeController::class, 'indexReg'])->name('admin.registration');
    Route::get('/attendees/register/{id}', [MeetingAttendeeController::class, 'showRegistrationForm'])->name('admin.attendees.register');
    Route::post('/attendees/store', [MeetingAttendeeController::class, 'store'])->name('admin.attendees.store');
    Route::get('/admin/attendees/view/{id}', [MeetingAttendeeController::class, 'viewAttendees'])->name('admin.attendees.view');

    // Protect admin attendee import and template download routes
    Route::get('/admin/attendees/import', [MeetingAttendeeController::class, 'showImportForm'])->name('attendees.import.form');
    Route::post('/admin/attendees/import', [MeetingAttendeeController::class, 'import'])->name('attendees.import');
    Route::get('/admin/attendees/download-template', [MeetingAttendeeController::class, 'downloadTemplate'])->name('attendees.download.template');

    // Attach document to booking (admin sidebar)
    Route::get('/admin/attach-document', [App\Http\Controllers\AdminController::class, 'showAttachDocument'])->name('admin.attach-document');
    Route::post('/admin/upload-document', [App\Http\Controllers\AdminController::class, 'uploadBookingDocument'])->name('admin.upload-document');
});
Route::post('/register/attendees/verify', [App\Http\Controllers\MeetingAttendeeController::class, 'verify'])->name('register.attendees.verify');


Route::middleware(['auth', 'role:register'])->group(function () {
    Route::get('/register/index', [RegisterController::class, 'index'])->name('register.index');

    Route::get('/register/attendees', [RegisterController::class, 'index'])->name('register.attendees.index');

    Route::get('/register/attendees/register/{id}', [RegisterController::class, 'showRegistrationForm'])->name('register.attendees.register');

    Route::post('/register/attendees/store', [RegisterController::class, 'store'])->name('register.attendees.store');

    Route::post('/register/attendees/upload-flyer/{bookingId}', [RegisterController::class, 'uploadFlyerForBooking'])->name('register.attendees.uploadFlyerForBooking');
});

Route::get('/enter-code', function () {
    return view('auth.enter-code');
})->name('enter.code');

Route::post('/enter-code', [App\Http\Controllers\MeetingAttendeeController::class, 'handleEnterCode'])->name('enter.code.submit');

Route::get('/register/attendees/register-/{id}', [App\Http\Controllers\RegisterController::class, 'showRegistrationReplicaForm'])->name('admin.attendees.register-replica');
Route::post('/register/attendees/store', [RegisterController::class, 'storeReplica'])->name('register.attendees.store-replica');

// Public route to fetch documents for a booking (AJAX)
Route::get('/booking/{booking}/documents', [App\Http\Controllers\MeetingAttendeeController::class, 'documents'])->name('booking.documents');

// Route to delete a booking document
Route::delete('/booking/document/{id}', [App\Http\Controllers\MeetingAttendeeController::class, 'deleteDocument'])->name('booking.document.delete');
