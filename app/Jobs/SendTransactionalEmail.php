<?php

namespace App\Jobs;

use App\Models\OutboundEmail;
use App\Services\Email\Email;
use App\Services\Email\EmailApiManager;
use App\Services\Email\NoDriversLeftException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendTransactionalEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Email $email, protected string $recipient)
    {
    }

    /**
     * @throws NoDriversLeftException
     */
    public function handle(EmailApiManager $emailApiManager): bool
    {
        foreach ($emailApiManager->getDrivers() as $driver) {
            $outboundEmail = new OutboundEmail();

            $outboundEmail->success = true;
            $outboundEmail->driver = $driver;
            $outboundEmail->recipient = $this->recipient;
            $outboundEmail->subject = $this->email->getSubject();
            $outboundEmail->content = $this->email->getContent();

            try {
                $emailApiManager->driver($driver)->send($this->email, $this->recipient);

                $outboundEmail->save();

                return true;
            } catch (Throwable $exception) {
                $outboundEmail->success = false;
                $outboundEmail->error_message = $exception->getMessage();

                $outboundEmail->save();
            }
        }

        throw new NoDriversLeftException();
    }
}
