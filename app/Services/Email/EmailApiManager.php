<?php

namespace App\Services\Email;

use App\Services\Email\Drivers\MailjetDriver;
use App\Services\Email\Drivers\SendgridDriver;
use Illuminate\Support\Manager;

/**
 * @method EmailDriverContract driver(string $driver = null)
 */
class EmailApiManager extends Manager
{

    public function getDefaultDriver(): string
    {
        return $this->getDrivers()[array_key_first($this->getDrivers())];
    }

    public function getDrivers(): array
    {
        return array_keys($this->config->get('services.email'));
    }

    public function createSendgridDriver()
    {
        return app()->make(SendgridDriver::class);
    }

    public function createMailjetDriver()
    {
        return app()->make(MailjetDriver::class);
    }
}
