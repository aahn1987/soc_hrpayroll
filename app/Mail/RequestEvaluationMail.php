<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class RequestEvaluationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $approveurl;
    public function __construct($data, $approveurl)
    {
        $this->data = $data;
        $this->approveurl = $approveurl;
    }

    public function build()
    {
        return $this->from('evaluation@my-soc.org', 'SOC Portal Evaluation')
            ->subject('New Evaluation Request From ' . $this->data['fullname'] . ' (' . $this->data['soc_reference'] . ')')
            ->view('emails.evalution_request');
    }
}
