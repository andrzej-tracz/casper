<?php

namespace Tests\Browser\Pages\Auth;

use App\Casper\Model\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
        $user = User::first();

        $browser
            ->value('#email', $user->email)
            ->value('#password', 'secret')
            ->press('Login');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
