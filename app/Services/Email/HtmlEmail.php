<?php

namespace App\Services\Email;

class HtmlEmail extends Email
{

    protected function getLineSeparator(): string
    {
        return '<br>';
    }
}
