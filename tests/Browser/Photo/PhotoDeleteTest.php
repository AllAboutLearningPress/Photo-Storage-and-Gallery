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
    private  $delete_modal_open_button_selector = "button[title='Delete image']";
    private $delete_button_on_modal = "#delete-photo-button";
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


            $text_on_delete_modal = 'Are you sure about';
            $viewing_trashed_photo_text = 'You are viewing a trashed photo';

            $browser->loginAs($user)
                ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->assertVisible($this->delete_modal_open_button_selector)
                //->assertSee('Are you sure about deleting')
                ->click($this->delete_modal_open_button_selector)
                ->waitForText($text_on_delete_modal, 5)
                ->assertSee($text_on_delete_modal)
                ->assertVisible($this->delete_button_on_modal)
                ->click($this->delete_button_on_modal)
                // notificator toast will show if photo is deleted successfullybgz
                ->waitForText('Photo moved to trash', 10)

                ->visitRoute('photos.trash')
                ->waitFor("img[src*='{$photo->file_name}']")
                ->click('.pig-figure')
                ->waitForText($viewing_trashed_photo_text, 5)
                ->assertSee($viewing_trashed_photo_text);

            $deletedPhoto = Photo::withTrashed()->find($photo->id);
            $this->assertNotNull($deletedPhoto->deleted_at);
        });
    }

    public function test_photo_permanent_delete_feature()
    {
        $this->artisan('db:seed --class=UserSeeder');
        $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) {
            $photo = Photo::find(1);
            $this->assertNotNull($photo);
            $user = User::find(1);
            $this->assertNotNull($user);


            $photo->delete();

            $browser->loginAs($user)
                ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->assertVisible($this->delete_modal_open_button_selector)
                //->assertSee('Are you sure about deleting')
                ->click($this->delete_modal_open_button_selector)
                ->waitForText('permanently', 5)
                // ->pause(54558888888)
                // ->assertVisible('permanently')
                ->assertVisible($this->delete_button_on_modal)
                ->click($this->delete_button_on_modal)
                ->waitForText('Photo permanently deleted', 5);
            $this->assertNull(Photo::find($photo->id));
        });
    }
}
