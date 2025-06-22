<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $leavename;
    public $approveurl;

    public function __construct($data, $leavename, $approveurl)
    {
        $this->data = $data;
        $this->leavename = $leavename;
        $this->approveurl = $approveurl;
    }

    public function build()
    {
        return $this->from('leaves@my-soc.org', 'SOC Portal Leaves')
            ->subject('New Leave Request From ' . $this->data['fullname'] . ' (' . $this->data['soc_reference'] . ')')
            ->view('emails.leave_request');
    }
}
