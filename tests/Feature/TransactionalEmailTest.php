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
    public function it_validates_post_request()
    {
        $this->postJson(route('send-transactional-emails'))
            ->assertStatus(422)
            ->assertJson(['message' => 'The given data was invalid.'])
            ->assertJsonStructure([
                'message',
                'errors' => ['type', 'recipients', 'subject', 'content']
            ]);
    }
}
