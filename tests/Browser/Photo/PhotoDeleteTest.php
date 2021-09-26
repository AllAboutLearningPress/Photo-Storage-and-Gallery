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
    private $delete_modal_open_button_selector = "button[title='Delete image']";
    private $delete_button_on_modal = "#delete-photo-button";
    private $viewing_trashed_photo_text = 'You are viewing a trashed photo';


    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=UserSeeder');
        $this->artisan('db:seed --class=PhotoSeeder');
    }

    /**
     * Testing if photo is seen in trash after deleting
     *
     * @return void
     */
    public function test_if_photo_goes_to_trash_after_deleting()
    {
        // will  add the user with id 1
        // $this->artisan('db:seed --class=UserSeeder');
        // $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) {
            $photo = Photo::find(1);
            $this->assertNotNull($photo);
            $user = User::find(1);
            $this->assertNotNull($user);

            // selectors


            $text_on_delete_modal = 'Are you sure about';

            $browser->loginAs($user)->assertAuthenticatedAs($user)
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
                ->waitForText($this->viewing_trashed_photo_text, 5)
                ->assertSee($this->viewing_trashed_photo_text);

            $deletedPhoto = Photo::withTrashed()->find($photo->id);
            $this->assertNotNull($deletedPhoto->deleted_at);
        });
    }

    public function test_photo_permanent_delete_feature()
    {
        // $this->artisan('db:seed --class=UserSeeder');
        // $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) {
            $photo = Photo::find(1);
            $this->assertNotNull($photo);
            $user = User::latest()->first();
            $this->assertNotNull($user);

            $browser->loginAs($user)
                ->visit("_dusk/login/" . $user->id)
                ->assertAuthenticatedAs($user);

            $photo->delete();


            $browser->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->waitFor($this->delete_modal_open_button_selector, 10)
                ->assertVisible($this->delete_modal_open_button_selector)
                //->assertSee('Are you sure about deleting')
                ->click($this->delete_modal_open_button_selector)
                ->waitForText('permanently', 5)
                // ->assertVisible('permanently')
                ->assertVisible($this->delete_button_on_modal)
                ->click($this->delete_button_on_modal)
                ->waitForText('Photo permanently deleted', 5);
            $this->assertNull(Photo::find($photo->id));
        });
    }

    public function test_if_photo_gets_restored_when_clicking_restore_btn_after_delete()
    {
        // $this->artisan('db:seed --class=UserSeeder');
        // $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) {
            $photo = Photo::find(1);
            $this->assertNotNull($photo);

            $user = User::find(1);
            $this->assertNotNull($user);

            $restore_photo_button_selector = "button[title='Restore Photo']";
            $browser->loginAs($user)
                ->visit("_dusk/login/" . $user->id)
                ->assertAuthenticatedAs($user);

            $photo->delete();
            $browser->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                //if photo is deleted then "You are viewing a trashed photo" will be shown on the bottom-left
                ->waitForText($this->viewing_trashed_photo_text, 5)
                ->assertSee($this->viewing_trashed_photo_text)
                ->assertVisible($restore_photo_button_selector) //checking if restore button is visible
                ->click($restore_photo_button_selector) //clicking on restore button to initiate restore
                ->waitForText('Photo restored', 10) //if photo is restored successfully then "Photo restored" toast will be visible
                ->assertNotPresent($restore_photo_button_selector);

            // if photo is successfully restored then deleted_at column
            // will be set to null
            $this->assertNull(Photo::find($photo->id)->deleted_at);
        });
    }
}
