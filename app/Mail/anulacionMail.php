<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class anulacionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
       $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $email =  $this->view('emails.anulacion',)
                  ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                  ->subject($this->data["subject"])
                  ->with($this->data["parametros"]);
        return $email;
    }
}
