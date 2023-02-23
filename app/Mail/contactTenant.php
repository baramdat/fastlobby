<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contactTenant extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $email;
    public function __construct($data,$email)
    {
        $this->data = $data;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Visitor')

        ->from('donotreply@fastlobby.com', env('MAIL_FROM'))
        ->replyTo($this->email, env('MAIL_FROM'))
        ->view('templates.email.conatct_external',$this->data);
    }
}
