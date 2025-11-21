<?php

namespace App\Jobs;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class VerifyUserEmailJob implements ShouldQueue
{
    use Queueable;
    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        event(new Registered($this->user));
    }
}
