<?php

namespace App\Services\Email;

use Illuminate\Support\Manager;

/**
 * @method EmailApiContract driver(string $driver = null)
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
}
