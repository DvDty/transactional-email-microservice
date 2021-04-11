<?php

namespace App\Console\Commands;

use App\Models\OutboundEmail;
use Illuminate\Console\Command;

class EmailLog extends Command
{

    protected $signature = 'email:log';

    protected $description = 'Display outbound emails';

    public function handle(): int
    {
        $columns = collect([
            'id',
            'success',
            'driver',
            'recipient',
            'subject',
            'created_at',
        ]);

        $this->table(
            $columns->map(fn($column) => ucfirst($column))->all(),
            OutboundEmail::all($columns->all()),
        );

        return 0;
    }
}
