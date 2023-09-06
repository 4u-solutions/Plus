<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {

        $email =  $this->view('emails.boletos')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject($this->data["subject"])
                    ->with([ 'name' => $this->data['name'],'titulo' => $this->data['titulo']]);

       foreach($this->data['attachemnts'] AS $kes=>$values){
         $email->attach($values, [
                         'as' => $this->data['titulo'].' Boleto '.($kes+1),
                         'mime' => 'application/pdf',
                     ]);
       }

        return $email;
    }
}
