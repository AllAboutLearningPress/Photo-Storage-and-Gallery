<?php

namespace Tests\Browser;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotoDeleteTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * Testing if photo is seen in trash after deleting
     *
     * @return void
     */
    public function test_if_photo_goes_to_trash_after_deleting()
    {
        // will  add the user with id 1
        $this->artisan('db:seed --class=UserSeeder');
        $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) {
            $photo = Photo::find(1);
            $this->assertNotNull($photo);
            $user = User::find(1);
            $this->assertNotNull($user);

            // selectors
            $delete_modal_open_button_selector = "button[title='Delete image']";
            $delete_button_on_modal = "#delete-photo-button";
            $text_on_delete_modal = 'Are you sure about';
            $viewing_trashed_photo_text = 'You are viewing a trashed photo';

            $browser->loginAs($user)
                ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->assertVisible($delete_modal_open_button_selector)
                //->assertSee('Are you sure about deleting')
                ->click($delete_modal_open_button_selector)
                ->waitForText($text_on_delete_modal, 5)
                ->assertSee($text_on_delete_modal)
                ->assertVisible($delete_button_on_modal)
                ->click($delete_button_on_modal)
                // notificator toast will show if photo is deleted successfullybgz
                ->waitForText('Photo moved to trash', 10)

                ->visitRoute('photos.trash')
                ->waitFor("img[src*='{$photo->file_name}']")
                ->click('.pig-figure')
                ->waitForText($viewing_trashed_photo_text, 5)
                ->assertSee($viewing_trashed_photo_text);
        });
    }
}
