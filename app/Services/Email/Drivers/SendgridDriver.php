<?php

namespace App\Services\Email\Drivers;

use App\Services\Email\Email;
use App\Services\Email\EmailDriverContract;
use SendGrid as SendgridClient;
use SendGrid\Mail\Mail as SendgridEmail;

class SendgridDriver implements EmailDriverContract
{

    public SendgridClient $sendGridClient;
    public SendgridEmail $sendgridEmail;

    public function __construct(SendgridClient $sendGridClient, SendgridEmail $sendgridEmail)
    {
        $this->sendGridClient = $sendGridClient;
        $this->sendgridEmail = $sendgridEmail;

        $this->sendgridEmail->setFrom(config('mail.from.address'), config('mail.from.name'));
    }

    public function send(Email $email, string $recipient): int
    {
        $this->sendgridEmail->addTo($recipient);
        $this->sendgridEmail->setSubject($email->getSubject());
        $this->sendgridEmail->addContent($email->getContentType(), $email->getContent());

        $response = $this->sendGridClient->send($this->sendgridEmail);

        return $response->statusCode();
    }
}
