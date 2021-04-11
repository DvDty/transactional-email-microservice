<?php

namespace App\Services\Email;

class PlainTextEmail extends Email
{

    protected function getLineSeparator(): string
    {
        return "\n";
    }
}
