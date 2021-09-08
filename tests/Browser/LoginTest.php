<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Tests login and logout feature
     *
     * @return void
     */
    public function testLoginAndLogout()
    {

        // creating the user for testing
        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->browse(function (Browser $browser)  use ($user) {
            $browser->visit('/')
                ->assertSee('Enter')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press(".login__submit-btn")
                ->assertSee('Notifications')
                ->press('Logout')
                ->waitFor('.login__submit-btn')
                ->assertSee('Enter');
        });
    }
}
