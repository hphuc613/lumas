<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $data;

    /**
     * NotificationEvent constructor.
     * @param $data
     */
    public function __construct($data){
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(){
        return new PrivateChannel('send-message');
    }
}
