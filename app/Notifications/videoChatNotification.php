<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class videoChatNotification extends Notification
{
    use Queueable;
    protected $sender;
    protected $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender, $message)
    {
        $this->sender = $sender;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $sender = $this->sender;
        $message = $this->message;
        $roomName = $message->room_name;
        $id = Crypt::encryptString($message);
        return [
            'id' => $id,
            // 'sender_id' => $sender->id,
            'title' => ucwords($sender->first_name) . ' ' . ucwords($sender->last_name),
            'message' => ucwords($sender->first_name) . ' ' . 'is inviting you for a video chat.',
            'route' => url('/room/join/' . $roomName),
        ];
    }
}
