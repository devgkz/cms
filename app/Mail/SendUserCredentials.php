<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserCredentials extends Mailable
{
    use Queueable, SerializesModels;
    
    public $name;
    public $email;
    public $role;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Регистрация в CMS')
                    ->markdown('mail/send_user_credentials');
    }
}
