<?php

namespace App\Services\Email;

interface EmailDriverContract
{
    public function send(Email $email, string $recipient): int;
}
