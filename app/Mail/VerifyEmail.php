<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationToken;

    public function __construct(User $user, $verificationToken)
    {
        $this->user = $user;
        $this->verificationToken = $verificationToken;
    }

    public function build()
    {
        return $this->view('emails.verify')
            ->subject('Verify Email Address')
            ->with([
                'verificationUrl' => url('/api/auth/verify-email?token=' . $this->verificationToken),
            ]);
    }
}
