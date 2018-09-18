<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SocialFacebookTest extends TestCase
{
    /**
     * @test
     */
    public function it_redirects_to_facebook()
    {
        $response = $this->get('auth/fb/redirect');

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSee('Redirecting to https://www.facebook.com/v3.0/dialog/oauth');
    }
}
