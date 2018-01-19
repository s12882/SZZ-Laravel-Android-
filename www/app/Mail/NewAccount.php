<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class NewAccount extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Nowe konto")->view('email.new-account',[
            'user' => $this->user,
            'password' => $this->password,
            'loginURL' => env('APP_URL')."/login"
            ]);
    }
}
