<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MeetingRoom;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $totalRooms = MeetingRoom::count();
        // $currentTime = Carbon::now();

        // // Get count of rooms currently in use
        // $roomsInUse = Booking::whereRaw("ADDTIME(time, SEC_TO_TIME(duration * 3600)) > ?", [$currentTime])
        //                     ->where('time', '<=', $currentTime)
        //                     ->count();

        // // Calculate available rooms
        // $availableRooms = max($totalRooms - $roomsInUse, 0);

        // // Share with all views
        // View::share('availableRooms', $availableRooms);

        // Share $rooms with the admin sidebar layout for the booking modal
        \Illuminate\Support\Facades\View::composer('layouts.admin-sidebar', function ($view) {
            $view->with('rooms', \App\Models\MeetingRoom::all());
        });
    }
}
