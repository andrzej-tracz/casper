<?php

namespace Tests\Browser\Pages\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class RegisterPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
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
    }

    public function createAccount(Browser $browser)
    {
        $faker = \Faker\Factory::create();

        $browser
            ->value('@nickname_input', Str::slug($faker->name))
            ->value('@email_input', $faker->email)
            ->value('@password_input', 'secret')
            ->value('@password_confirmation_input', 'secret')
            ->value('@gender_input', 'male')
            ->value('@birth_date_input', Carbon::now()->subYear(20)->format('Y-m-d'))
            ->press('Register');

        $browser->pause(200);
        $browser->screenshot('register');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@nickname_input' => '#nickname',
            '@email_input' => '#email',
            '@password_input' => '#password',
            '@password_confirmation_input' => '#password-confirm',
            '@gender_input' => '#gender',
            '@birth_date_input' => '#birth_date',
        ];
    }
}
