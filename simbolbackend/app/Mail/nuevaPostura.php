<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class nuevaPostura extends Mailable
{
    use Queueable, SerializesModels;

    public $postura;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($postura)
    {
        //
        $this->postura = $postura;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.nuevaPostura');
    }
}
