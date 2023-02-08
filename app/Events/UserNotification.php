<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $recieverId;
    public $notificationId;
    public $url;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message , $recieverId ,$notificationId,$url)
    {
        $this->message = $message;
        $this->recieverId = $recieverId;
        $this->notificationId=$notificationId;
        $this->url=$url;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private-message.'.$this->recieverId);
    }
}
