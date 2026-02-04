<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class MailServices
{
    public function sendMail(string $to, string $subject, string $body): void
    {
        Mail::raw($body, function ($m) use ($to, $subject) {
            $m->to($to)->subject($subject);
        });
    }
}