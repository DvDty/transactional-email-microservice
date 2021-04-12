<?php

namespace Tests\Feature;

use App\Jobs\SendTransactionalEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Mailjet\Client as MailjetClient;
use Mailjet\Response as MailjetResponse;
use Mockery;
use Mockery\MockInterface;
use SendGrid as SendgridClient;
use SendGrid\Response as SendgridResponse;
use Tests\TestCase;

class TransactionalEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_validates_required_fields()
    {
        $this->postJson(route('send-transactional-emails'))
            ->assertStatus(422)
            ->assertJson(['message' => 'The given data was invalid.'])
            ->assertJsonStructure([
                'message',
                'errors' => ['type', 'recipients', 'subject', 'content'],
            ]);
    }

    /**
     * @test
     */
    public function it_validates_recipients_are_valid_emails()
    {
        $this->postJson(
            route('send-transactional-emails'),
            $this->getEmailData(['recipients' => ['invalid-email']]),
        )
            ->assertStatus(422)
            ->assertJson(['message' => 'The given data was invalid.'])
            ->assertJsonStructure([
                'message',
                'errors' => ['recipients.0'],
            ]);
    }

    /**
     * @test
     */
    public function it_adds_emails_to_the_queue()
    {
        Bus::fake();

        $this->postJson(route('send-transactional-emails'), $this->getEmailData())
            ->assertStatus(202);

        Bus::assertDispatched(SendTransactionalEmail::class);
    }

    /**
     * @test
     */
    public function it_sends_emails_through_external_service()
    {
        $this->mock(SendgridClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')
                ->once()
                ->andReturn(new SendgridClient\Response(202));
        });

        $this->postJson(route('send-transactional-emails'), $this->getEmailData());

        $this->assertDatabaseCount('outbound_emails', 1);

        $this->assertDatabaseHas('outbound_emails', [
            'success'       => 1,
            'recipient'     => $this->getEmailData()['recipients'][0],
            'subject'       => $this->getEmailData()['subject'],
            'error_message' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_sends_emails_through_fallback_external_service()
    {
        $errorMessage = 'Invalid request';

        $this->mock(SendgridClient::class, function (MockInterface $mock) use ($errorMessage) {
            $sendgridResponse = Mockery::mock(SendgridResponse::class);

            $sendgridResponse->shouldReceive('statusCode')->once()->andReturn(400);

            $sendgridResponse->shouldReceive('body')->once()->andReturn($errorMessage);

            $mock->shouldReceive('send')->once()->andReturn($sendgridResponse);
        });

        $this->mock(MailjetClient::class, function (MockInterface $mock) {
            $mailjetResponse = Mockery::mock(MailjetResponse::class);

            $mailjetResponse->shouldReceive('success')->once()->andReturn(true);

            $mailjetResponse->shouldReceive('getStatus')->once()->andReturn(200);

            $mock->shouldReceive('post')->once()->andReturn($mailjetResponse);
        });

        $this->postJson(route('send-transactional-emails'), $this->getEmailData());

        $this->assertDatabaseCount('outbound_emails', 2);

        $this->assertDatabaseHas('outbound_emails', [
            'success'       => 0,
            'recipient'     => $this->getEmailData()['recipients'][0],
            'subject'       => $this->getEmailData()['subject'],
            'error_message' => $errorMessage,
        ]);

        $this->assertDatabaseHas('outbound_emails', [
            'success'       => 1,
            'recipient'     => $this->getEmailData()['recipients'][0],
            'subject'       => $this->getEmailData()['subject'],
            'error_message' => null,
        ]);
    }

    protected function getEmailData(array $attributes = []): array
    {
        return array_merge([
            'type'       => 'text',
            'recipients' => ['example@example.com'],
            'subject'    => 'Subject',
            'content'    => ['Line 1', 'Line 2'],
        ], $attributes);
    }
}
