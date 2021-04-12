<?php

namespace App\Services\Email\Drivers;

use App\Services\Email\Email;
use App\Services\Email\EmailDriverContract;
use App\Services\Email\HtmlEmail;
use App\Services\Email\PlainTextEmail;
use Exception;
use Mailjet\Client as MailjetClient;
use Mailjet\Resources;

class MailjetDriver implements EmailDriverContract
{

    public function __construct(protected MailjetClient $mailjetClient)
    {
    }

    /**
     * @throws Exception
     */
    public function send(Email $email, string $recipient): int
    {
        $plainText = $html = '';

        if ($email instanceof PlainTextEmail) {
            $plainText = $email->getContent();
        }

        if ($email instanceof HtmlEmail) {
            $html = $email->getContent();
        }

        $requestBody = [
            'Messages' => [[
                'From'     => [
                    'Email' => config('mail.from.address'),
                    'Name'  => config('mail.from.name'),
                ],
                'To'       => [['Email' => $recipient]],
                'Subject'  => $email->getSubject(),
                'TextPart' => $plainText,
                'HTMLPart' => $html,
            ]],
        ];

        $response = $this->mailjetClient->post(Resources::$Email, ['body' => $requestBody]);

        if (!$response->success()) {
            throw new Exception($response->getBody());
        }

        return $response->getStatus();
    }
}
