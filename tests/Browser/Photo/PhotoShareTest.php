<?php

namespace Tests\Browser;

use App\Models\Photo;
use App\Models\SharePhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Traits\DatabaseMigrationsWithSeeder;
use Tests\DuskTestCase;

class PhotoShareTest extends DuskTestCase
{
    use DatabaseMigrationsWithSeeder;
    public function test_sharable_link_with_only_view_permission()
    {
        $this->browse(function (Browser $browser, $second) {

            $photo = Photo::find(1);
            $this->assertNotNull($photo);
            $user = User::find(1);
            $this->assertNotNull($user);
            $share_modal_open_button_selector = "button[title='Share image']";
            $browser->loginAs($user)
                ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                ->waitFor($share_modal_open_button_selector, 5)
                ->assertVisible($share_modal_open_button_selector)
                ->click($share_modal_open_button_selector)
                ->waitFor("#shareModal", 5);

            $photo_share_link_input_ele = "#photo-share-link";
            $open_share_link_btn = "#open-share-btn";
            // checking for all the buttons
            $browser->assertVisible("button[data-bs-target='#shareModal']") // share cancel button
                ->assertVisible($open_share_link_btn)
                ->assertVisible("#copy-share-link") // link copy button
                ->assertVisible($photo_share_link_input_ele);


            // checking if share link input element is correct
            $share = SharePhoto::latest()->first();
            $this->assertNotNull($share);
            $browser->assertValue("#photo-share-link", route('share.show', ['key' => $share->share_key])); //->click($open_share_link_btn);
            $share_url = $browser->value($photo_share_link_input_ele);
            //$browser->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $second->visit($share_url)
                ->assertVisible("img[src*='preview_photos/{$photo->file_name}']");
            //$browser->pause(55555588888);
        });
    }
}
