<?php

namespace Tests\Browser;

use App\Models\Photo;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotoTagTest extends DuskTestCase
{
    use DatabaseMigrations;
    private $testing_tag = 'testing-tag';
    private $newTagSelectorInTagList = "a[href*='tags/testing-tag']";
    private $showDetailsButtonSelector = ".image-view__show-details";
    private $tagInputSelector = ".js-tags__input";
    // public function setUp(): void
    // {
    //     parent::setUp();
    //     $this->artisan('db:seed');
    //     $this->artisan('db:seed --class=PhotoSeeder');
    // }

    public function test_adding_tag_using_tag_input_box_in_photoview()
    {
        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->artisan('db:seed --class=PhotoSeeder');

        $this->browse(function (Browser $browser) use ($user) {
            $photo = Photo::latest()->first();
            $this->assertNotNull($photo);
            $addTagButtonSelector = "button[title='Add tag']";


            $tagInputFieldName = 'tag-input';
            // $newTagSelectorInTagList = "a[href*='tags/{$this->testing_tag}']";
            $browser->loginAs($user)
                ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->waitFor($this->showDetailsButtonSelector) // waiting for show-details buton to be laoded
                ->assertVisible($this->showDetailsButtonSelector) // checking if show details button is visible.
                ->click($this->showDetailsButtonSelector) // clicking the show details button to open info side bar
                ->waitFor($this->tagInputSelector, 5) // waiting for sidebar to be loaded
                ->assertVisible($this->tagInputSelector) // checking if side bar is loaded
                ->type($tagInputFieldName, $this->testing_tag) // typing tag in the tag input field
                ->assertVisible($addTagButtonSelector) // check if add button on the right side of input field is visible
                ->click($addTagButtonSelector) // clicking the add button to add the tag
                ->waitFor($this->newTagSelectorInTagList, 120) // waiting for new tag to appear in the taglist
                ->assertVisible($this->newTagSelectorInTagList); //  checking of new tag is visible in taglist
        });
    }


    public function test_open_photo_from_tag_attached_photos()
    {

        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->artisan('db:seed --class=PhotoSeeder');
        $this->browse(function (Browser $browser) use ($user) {

            $photo = Photo::where('height', '!=', null)->latest()->first();
            // adding testing tag to the photo
            $tag = Tag::firstOrCreate(['name' => $this->testing_tag]);

            // adding tag to photo
            $photo->tags()->attach($tag->id);

            $uploaded_photo_thumb_in_gallery_selector = "img[src*='thumbnails/{$photo->file_name}']";

            $browser
                ->loginAs($user)
                ->pause(3000)
                ->visitRoute('tags.index')
                ->waitFor('.main div h2',)
                ->assertSee('Popular tags')
                ->assertSee($this->testing_tag) // check if selected tag is in the tag list
                ->click($this->newTagSelectorInTagList) // clicking the tag to view all associated photos of that tag
                ->waitFor($uploaded_photo_thumb_in_gallery_selector, 10) // checking if photo (img) element is loaded
                ->assertVisible($uploaded_photo_thumb_in_gallery_selector)
                // using javascript to click on the image. Because dusk doesnt support
                // clicking on the parent element
                ->script(
                    'document.querySelector("' . $uploaded_photo_thumb_in_gallery_selector . '").parentElement.click();'
                );  // clicking the photo from gallery view of selected tag

            $photoviewPhotoSelector = "img[src*='preview_photos/{$photo->file_name}']";
            $browser->waitFor($photoviewPhotoSelector) // waiting for preview-photo to be loaded
                ->assertVisible($photoviewPhotoSelector); // checking if preview-photo is visble
        });
    }


    public function test_delete_tag_from_photo()
    {
        $user = User::factory()->create([
            'email' => 'testing_user@example.com'
        ]);
        $this->artisan('db:seed --class=PhotoSeeder');

        $this->browse(function (Browser $browser) use ($user) {

            $photo = Photo::where('height', '!=', null)->latest()->first();
            // adding testing tag to the photo
            $tag = Tag::firstOrCreate(['name' => $this->testing_tag]);

            // adding tag to photo
            $photo->tags()->attach($tag->id);

            $uploaded_photo_thumb_in_gallery_selector = "img[src*='thumbnails/{$photo->file_name}']";
            $photoviewPhotoSelector = "img[src*='preview_photos/{$photo->file_name}']";
            $tagDeleteButtonSelector = $this->newTagSelectorInTagList . " button";
            $browser
                ->loginAs($user)
                // photo details sidebar on photoview uses 'sidebar-position-class'
                // from localstore decide if sidebar should be open or closed state
                // on laod. If sidebar-position-class is set to 'is-open' then sidebar
                // will be open.
                ->script("window.localStorage.setItem('sidebar-position-class', 'is-open')");

            $browser->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->waitFor($photoviewPhotoSelector)
                ->assertVisible($photoviewPhotoSelector)
                ->assertVisible($this->showDetailsButtonSelector)
                //->click($this->showDetailsButtonSelector)
                ->waitFor($this->tagInputSelector)
                ->waitFor($this->newTagSelectorInTagList, 10)
                ->assertVisible($this->newTagSelectorInTagList)
                ->assertVisible($tagDeleteButtonSelector)
                ->click($tagDeleteButtonSelector)
                ->waitFor('.toast-body', 10)
                ->assertPresent('.toast-body')
                ->assertSee('Tag removed')
                ->assertNotPresent($this->newTagSelectorInTagList);
        });
    }
}
