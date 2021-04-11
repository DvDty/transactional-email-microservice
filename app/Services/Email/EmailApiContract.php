<?php

namespace App\Services\Email;

interface EmailApiContract
{
    public function send(Email $email, string $recipient): int;
}
