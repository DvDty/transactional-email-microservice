<?php

namespace App\Console\Commands;

use App\Jobs\SendTransactionalEmail;
use App\Services\Email\Email;
use App\Services\Email\HtmlEmail;
use App\Services\Email\PlainTextEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SendTransactionalEmails extends Command
{

    protected $signature = 'email:send';

    protected $description = 'Send transactional email to multiple recipients through the queue';

    protected array $emailTypes;

    public function __construct()
    {
        parent::__construct();

        $this->setEmailTypes();
    }

    public function handle(): int
    {
        $emailType = $this->choice(
            trans('transactional_email.cli.email_type_prompt'),
            array_keys($this->emailTypes),
            0,
        );

        /** @var Email $email */
        $email = new $this->emailTypes[$emailType];

        $email->setSubject($this->ask(trans('transactional_email.cli.subject_prompt')));

        $this->info(trans('transactional_email.cli.content_explanation'));

        while (($contentLine = $this->ask(trans('transactional_email.cli.content_line_prompt'))) !== 'ready') {
            $email->addContent($contentLine);
        }

        $this->info(trans('transactional_email.cli.recipients_explanation'));

        $recipients = collect();

        while (($recipient = $this->ask(trans('transactional_email.cli.recipient_prompt'))) !== 'ready') {
            $validator = Validator::make(
                ['recipient' => $recipient],
                ['recipient' => 'required|email'],
            );

            if ($validator->fails()) {
                $this->error(trans('transactional_email.cli.recipient_validation_error', ['recipient' => $recipient]));

                continue;
            }

            $recipients->push($recipient);
        }

        $recipients = $recipients->unique();

        $confirmationPrompt = trans('transactional_email.cli.confirmation_prompt', [
            'subject'        => $email->getSubject(),
            'recipientCount' => $recipients->count(),
        ]);

        if ($this->confirm($confirmationPrompt, true)) {
            $this->withProgressBar($recipients, function (string $recipient) use ($email) {
                dispatch(new SendTransactionalEmail($email, $recipient));
            });

            $this->newLine();
            $this->info(trans('transactional_email.cli.success_message'));
        }

        $this->info(trans('transactional_email.cli.exiting_message'));

        return 0;
    }

    protected function setEmailTypes(): void
    {
        $this->emailTypes = [
            trans('transactional_email.cli.text_type') => PlainTextEmail::class,
            trans('transactional_email.cli.html_type') => HtmlEmail::class,
        ];
    }
}
