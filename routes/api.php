<?php

use App\Http\Controllers\TransactionalEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/send-transactional-emails', [TransactionalEmailController::class, 'send'])
    ->name('send-transactional-emails');
