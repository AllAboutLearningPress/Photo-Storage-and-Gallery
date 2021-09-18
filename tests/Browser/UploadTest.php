<?php

namespace Tests\Browser;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class UploadTest extends DuskTestCase
{
    //use DatabaseMigrations;
    // public function boot()
    // {
    //     $this->artisan('migrate:refresh');
    // }
    /**
     * Testing the photo upload feature
     *
     * @return void
     */
    public function testPhotoUpload()
    {
        $this->artisan('migrate:refresh');

        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->browse(function (Browser $browser) {
            $upload_input_selector = '.js-upload__input';
            $browser->loginAs(User::find(1))
                ->visit('/')
                // ->pause(10000)
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
    private $testing_tag = 'testing-tag';
    public function testTagInputField()
    {
        // $user = User::factory()->create([
        //     'email' => 'testing_user@example.com'
        // ]);

        $this->browse(function (Browser $browser) {
            $uploadedPhoto = Photo::where('title', 'city.jpeg')->latest()->first();
            $this->assertNotNull($uploadedPhoto);
            $addTagButtonSelector = "button[title='Add tag']";
            $showDetailsButtonSelector = ".image-view__show-details";
            $tagInputSelector = ".js-tags__input";
            $tagInputFieldName = 'tag-input';
            $newTagSelectorInTagList = "a[href*='tags/{$this->testing_tag}']";

            $browser->assertVisible($showDetailsButtonSelector)
                ->click($showDetailsButtonSelector)
                ->waitFor($tagInputSelector, 5)
                ->assertVisible($tagInputSelector)
                ->type($tagInputFieldName, $this->testing_tag)
                ->assertVisible($addTagButtonSelector)
                ->click($addTagButtonSelector)
                ->waitFor($newTagSelectorInTagList, 120)
                ->assertVisible($newTagSelectorInTagList);
        });
    }
    // public function testPhotoVisibleInTags()
    // {
    //     $this->browse(function (Browser $browser) {

    //         //
    //     });
    // }
}
