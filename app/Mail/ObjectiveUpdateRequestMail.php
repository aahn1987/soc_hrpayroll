<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ObjectiveUpdateRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $objectiveText;
    public $link;
    public $fullname;
    public $socReference;

    public function __construct($objectiveText, $link, $fullname, $socReference)
    {
        $this->objectiveText = $objectiveText;
        $this->link = $link;
        $this->fullname = $fullname;
        $this->socReference = $socReference;
    }

    public function build()
    {
        return $this->from('evaluation@my-soc.org', 'SOC Portal Evaluation')
            ->subject("Objective Update Request From {$this->fullname} ({$this->socReference})")
            ->markdown('emails.objective_update')
            ->with([
                'objectiveText' => $this->objectiveText,
                'link' => $this->link,
            ]);
    }
}
