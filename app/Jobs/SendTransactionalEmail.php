<?php

namespace App\Jobs;

use App\Services\Email\Email;
use App\Services\Email\EmailApiManager;
use App\Services\Email\NoDriversLeftException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
            //try {
            $emailApiManager->driver($driver)->send($this->email, $this->recipient);

            // log

            return true;
            //} catch (Throwable $exception) {
            //    // log
            //}
        }

        throw new NoDriversLeftException();
    }
}
