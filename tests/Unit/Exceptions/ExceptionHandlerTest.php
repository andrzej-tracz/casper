<?php

namespace Tests\Unit\Exceptions;

use App\Auth\Exceptions\FailedAuthorizationException;
use App\Exceptions\Handler;
use Illuminate\Container\Container;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

/**
 * Class ExceptionHandlerTest
 * @package Tests\Unit\Exceptions
 *
 * @group exceptions
 */
class ExceptionHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_redirect_response_when_authorization_exception_is_thrown()
    {
        $container = Container::getInstance();
        $handler = new Handler($container);
        $exception = new FailedAuthorizationException();

        /** @var $response RedirectResponse */
        $response =  $handler->render(null, $exception);
        $this->assertTrue($response instanceof RedirectResponse);

        /** @var $errors  \Illuminate\Support\ViewErrorBag */
        $errors = $response->getSession()->get('errors');
        $this->assertCount(1, $errors);
        $this->assertEquals('An error occurred during authorization, please try again.', $errors->first());
    }
}
