<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VcardCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $password,
        public string $loginUrl,
        public string $vcardUrl,
    ) {
    }

    public function build(): self
    {
        return $this->subject('Your vCard login credentials')
            ->view('emails.vcard-credentials');
    }
}
