<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionalEmailRequest;
use App\Jobs\SendTransactionalEmail;
use App\Services\Email\HtmlEmail;
use App\Services\Email\PlainTextEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TransactionalEmailController extends Controller
{
    public function send(TransactionalEmailRequest $request): JsonResponse
    {
        $email = $request->get('type') === 'html' ? new HtmlEmail() : new PlainTextEmail();

        $email->setSubject($request->get('subject'));

        foreach ($request->get('content') as $line) {
            $email->addContent($line);
        }

        foreach ($request->get('recipients') as $recipient) {
            dispatch(new SendTransactionalEmail($email, $recipient));
        }

        return response()->json([], Response::HTTP_ACCEPTED);
    }
}
