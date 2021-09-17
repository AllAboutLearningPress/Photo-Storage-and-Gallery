<?php

namespace Tests\Browser;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class UploadTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * Testing the photo upload feature
     *
     * @return void
     */
    public function testPhotoUpload()
    {
        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->browse(function (Browser $browser) {
            $upload_input_selector = '.js-upload__input';
            $browser->loginAs(User::find(1))
                ->visit('/')
                ->assertVisible('.header__action-btn__txt') // Upload button
                ->assertPresent($upload_input_selector) // upload input element
                ->attach($upload_input_selector, __DIR__ . '/photos/city.jpeg') // selecting the file to input element
                ->waitForText('Complete upload', 120) // text appears when upload is finished
                ->assertSee('Complete upload') // checking if text visible
                ->assertVisible(".btn-outline-success") // check if Complete Upload button is visible
                ->press(".btn-outline-success") // pressing the button. It will redirect to "/" (home)
                ->waitFor('.pig-loaded', 5) // class is available when image is loaded
                ->click('.pig-figure') // opening the first image
                ->waitFor('.image-view__pic') // this class is present in PhotoView (preview image wrapper div class)
                ->assertVisible('.image-view__pic');

            $uploadedPhoto = Photo::where('title', 'city.jpeg')->latest()->first();
            static::assertNotNull($uploadedPhoto); // checking if photo is entry is stored on DB
            $browser->visit(route(
                'photos.show',
                ['id' => $uploadedPhoto->id, 'slug' => $uploadedPhoto->slug]
            )) // visiting the PhotoView
                ->waitFor("img[src*='{$uploadedPhoto->file_name}']") // checking if element is loaded
                ->assertVisible("img[src*='{$uploadedPhoto->file_name}']"); // checking if photo is visible

        });
    }
}
