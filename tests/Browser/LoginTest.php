<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('optimize');
        $this->artisan('db:seed --class="UserSeeder"');
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoginAndLogout()
    {

        // dd(env('DB_DATABASE'));
        // dd(env('APP_ENV'));
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Enter');
            $browser->type('email', 'user@example.com');
            $browser->type('password', 'photo@12345pass!');
            $browser->press(".login__submit-btn")
                ->assertSee('Notifications');
            $browser->press('Logout')
                ->assertSee('Enter');
        });
    }
}
