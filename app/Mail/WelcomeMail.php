<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $webUrl;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;

        $this->webUrl = $user->user_type == BUYER ? env('WEB_URL') : route(routePrefix() . 'login');
    }

    public function build()
    {
        // return $this->subject('Welcome to Our App')
        //             ->markdown('emails.welcome');

        return $this->markdown('emails.welcome')
            ->subject('Welcome to')
            ->with([
                'user' => $this->user,

            ]);
    }
}
