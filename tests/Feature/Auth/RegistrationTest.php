<?php

namespace Tests\Feature\Auth;

use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_redirects_authenticated_users()
    {
        $this->actingAs(User::first());

        $response = $this->get('register');
        $response->assertStatus(302);
        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function it_validates_registration_data()
    {
        $response = $this->post('register', [
            'nickname' => null,
            'email' => null,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertSessionHasErrors([
            'nickname',
            'email',
        ]);
    }

    /**
     * @test
     */
    public function it_validates_password_confirmation()
    {
        $faker = \Faker\Factory::create();

        $response = $this->post('register', [
            'nickname' => $faker->userName,
            'email' => $faker->email,
            'password' => 'secret',
            'password_confirmation' => 'secret1',
        ]);

        $response->assertSessionHasErrors([
            'password'
        ]);
    }

    /**
     * @test
     */
    public function it_handles_registration_when_data_is_valid()
    {
        $faker = \Faker\Factory::create();
        $nick = $faker->userName;
        $email = $faker->email;

        $this->get('register')->assertSee('Register');

        $this->post('register', [
            'nickname' => $nick,
            'email' => $email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'gender' => 'male',
            'birth_date' => '1990-03-15',
        ]);

        $this->assertDatabaseHas('users', [
            'nickname' => $nick,
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function it_handles_registration_without_optional_parameters()
    {
        $faker = \Faker\Factory::create();
        $nick = $faker->userName;
        $email = $faker->email;

        $this->get('register')->assertSee('Register');

        $this->post('register', [
            'nickname' => $nick,
            'email' => $email,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseHas('users', [
            'nickname' => $nick,
            'email' => $email,
        ]);
    }
}
