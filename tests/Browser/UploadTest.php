<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UploadTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->artisan('db:seed --class="UserSeeder"');
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                ->visit('/')
                ->attach('.js-upload__input', __DIR__ . 'photos/city.jpeg')
                ->waitForText('Complete upload', 120)
                ->press(".btn-outline-succes")
                ->waitFor("#pig")
                ->pause(10000);


            // code to delete the uploaded file

        });
    }
}
