<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Browser\Pages\Auth;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\NearestEventsPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AppBrowserTest extends DuskTestCase
{
    use DatabaseTransactions;

    protected $publicPages = [
        HomePage::class,
        NearestEventsPage::class,
        Auth\LoginPage::class,
        Auth\ForgotPasswordPage::class
    ];

    /**
     * A basic browser test example
     *
     * @return void
     * @throws \Throwable
     */
    public function testPagesDisplay()
    {
        $this->browse(function (Browser $browser) {
            foreach ($this->publicPages as $page) {
                $browser->visit(new $page);
            }
        });
    }

    /**
     * @throws \Throwable
     */
    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Auth\RegisterPage())->createAccount();
            $browser->assertSee('My Events');
            $browser->assertSee('Logout');
        });
    }
}
