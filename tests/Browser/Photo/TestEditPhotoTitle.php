<?php

namespace Tests\Browser;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class TestEditPhotoTitle extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Testing if title can be edited from the ui in PhotoView page
     *
     * @return void
     */
    public function test_edit_photo_title_from_photo_view()
    {
        $this->artisan('migrate:refresh');

        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) {
            // this should return the photo seeded by PhotoSeeder
            // considering database was fresly migrated
            $photo = Photo::latest()->first();

            // generating a random title for cross checking
            // if title actually changed
            $changed_title = 'testing title changed' . bin2hex(random_bytes(8));

            $browser->loginAs(User::find(1))
                // photo details sidebar on photoview uses 'sidebar-position-class'
                // from localstore decide if sidebar should be open or closed state
                // on laod. If sidebar-position-class is set to 'is-open' then sidebar
                // will be open.
                ->script("window.localStorage.setItem('sidebar-position-class', 'is-open')");

            // edit pen icon button on the right side of title
            $title_edit_button_selector = ".js-editable__trigger";
            $browser->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->waitFor($title_edit_button_selector)
                ->assertVisible($title_edit_button_selector)
                ->click($title_edit_button_selector)
                ->waitFor('.is-editing')
                ->assertVisible('.js-editable__area')
                ->script("document.querySelector('.js-editable__area').value = '{$changed_title}'");

            // clicking elsewhere to trigger title save function
            $browser->click('#image-details-sidebar')
                ->pause(2000) // giving time for executing save title function
                ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug]) //reloading the page to see if title actually chagned
                ->assertSee($changed_title); // checking if changed title is present on page

            // checking if title has changed in database
            $updatedPhoto = Photo::find($photo->id);
            $this->assertNotNull($updatedPhoto);
            $this->assertTrue($updatedPhoto->title == $changed_title);
        });
    }
}
