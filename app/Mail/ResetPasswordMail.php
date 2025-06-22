<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $password;

    public function __construct($fullname, $password)
    {
        $this->fullname = $fullname;
        $this->password = $password;
    }

    public function build()
    {
        return $this->from('passwords@my-soc.org', 'SOC Portal Accounts')
            ->subject('Your New Login Details For SOC Portal')
            ->view('emails.reset_password');
    }
}
