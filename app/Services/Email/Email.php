<?php

namespace App\Services\Email;

abstract class Email
{
    protected string $subject;

    protected string $content = '';

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function addContent(string $content): void
    {
        if ($this->content) {
            $this->content .= $this->getLineSeparator();
        }

        $this->content .= $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getContentType(): string
    {
        return match (static::class) {
            PlainTextEmail::class => 'text/plain',
            HtmlEmail::class => 'text/html',
        };
    }

    abstract protected function getLineSeparator(): string;
}
