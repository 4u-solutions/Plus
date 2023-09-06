<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class voucher extends Mailable
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
      // dd($this->data["parametros"]);
      $email =  $this->view('emails.voucher',)
                  ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                  ->subject($this->data["subject"])
                  ->with($this->data["parametros"]);

     // foreach($this->data['attachemnts'] AS $kes=>$values){
     //   $email->attach($values, [
     //                   'as' => 'Boleto '.$kes,
     //                   'mime' => 'application/pdf',
     //               ]);
     // }

      // return $email;
        return $email;
    }
}
