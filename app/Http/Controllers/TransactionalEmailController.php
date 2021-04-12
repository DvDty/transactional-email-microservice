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
    /**
     * @OA\Post(
     *   path="/api/send-transactional-emails",
     *   summary="Send transactional emails through the queue",
     *   tags={"Transactional Emails"},
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *         @OA\Property(property="type", type="string", enum={"text", "html"}),
     *         @OA\Property(
     *            property="recipients",
     *            type="array",
     *            @OA\Items(type="string"),
     *            description="Each array element must be a valid email address"
     *         ),
     *         @OA\Property(property="subject", type="string"),
     *         @OA\Property(
     *            property="content",
     *            type="array",
     *            @OA\Items(type="string"),
     *            description="Each array element is separate line"
     *         )
     *      )
     *   ),
     *   @OA\Response(response=202, description="Accepted"),
     *   @OA\Response(
     *     response=422,
     *     description="Validation errors",
     *     @OA\JsonContent(
     *          @OA\Property(property="message", type="string"),
     *          @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *     )
     *   )
     * )
     */
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
