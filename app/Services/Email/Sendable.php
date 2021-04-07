<?php

namespace App\Services\Email;

interface Sendable
{
    public function send(): bool;
}
