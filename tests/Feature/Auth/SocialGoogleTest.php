<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SocialGoogleTest extends TestCase
{
    /**
     * @test
     */
    public function it_redirects_to_google()
    {
        $response = $this->get('auth/google/redirect');

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSee('Redirecting to https://accounts.google.com/o/oauth2/auth');
    }
}
