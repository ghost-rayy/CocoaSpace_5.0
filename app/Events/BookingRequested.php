<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function broadcastOn()
    {
        return new Channel('booking-channel');
    }

    public function broadcastWith()
    {
        return [
            'name' => $this->booking->user->name,
            'room' => $this->booking->room->name,
            'date' => $this->booking->date,
            'time' => $this->booking->time,
        ];
    }

    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}
