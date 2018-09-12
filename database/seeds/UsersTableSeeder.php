<?php

use Illuminate\Database\Seeder;
use \App\Casper\Model\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->each(function (User $user) {
            $user->events()->save(factory(\App\Casper\Model\Event::class)->make());
        });
    }
}
