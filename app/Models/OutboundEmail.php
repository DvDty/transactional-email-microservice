<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property boolean success
 * @property string driver
 * @property string recipient
 * @property string subject
 * @property string content
 * @property string error_message
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class OutboundEmail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'success' => 'boolean'
    ];
}

