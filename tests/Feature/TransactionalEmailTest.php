<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionalEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_validates_required_fields_on_post_request()
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
    public function it_validates_recipients_are_valid_emails_on_post_request()
    {
        $this->postJson(route('send-transactional-emails'), [
            'type'       => 'text',
            'recipients' => ['invalid-email'],
            'subject'    => 'Subject',
            'content'    => ['Line 1', 'Line 2'],
        ])
            ->assertStatus(422)
            ->assertJson(['message' => 'The given data was invalid.'])
            ->assertJsonStructure([
                'message',
                'errors' => ['recipients.0'],
            ]);
    }
}
