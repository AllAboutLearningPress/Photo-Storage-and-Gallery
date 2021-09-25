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

    private $download_btn_selector = "#download-btn";
    private $view_info_btn_selector = "#show-details-btn";
    public function test_sharable_link_with_each_permission()
    {
        $this->browse(
            function (Browser $first, Browser $second) {

                $photo = Photo::find(1);
                $this->assertNotNull($photo);
                $user = User::find(1);
                $this->assertNotNull($user);
                $share_modal_open_button_selector = "button[title='Share image']";
                $first->loginAs($user)
                    ->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                    ->waitFor($share_modal_open_button_selector, 5)
                    ->assertVisible($share_modal_open_button_selector)
                    ->click($share_modal_open_button_selector)
                    ->waitFor("#shareModal", 5);

                $photo_share_link_input_ele = "#photo-share-link";
                $open_share_link_btn = "#open-share-btn";


                // checking for all the buttons on share modal
                $first->assertVisible("button[data-bs-target='#shareModal']") // share cancel button
                    ->assertVisible($open_share_link_btn)
                    ->assertVisible("#copy-share-link") // link copy button
                    ->assertVisible($photo_share_link_input_ele);

                // looping through the checkboxes. In each iteration it will check if
                // correct buttons are visible and other buttons are missing.
                foreach (['', '#share-view-info-checkbox', '#share-download-checkbox'] as $permission_check) {
                    // checking if share link input element is correct
                    $old_share_url = $first->value($photo_share_link_input_ele);
                    if (strlen($permission_check) > 0) {

                        $first->waitFor($permission_check)->check($permission_check);
                    }
                    $share_url = $first->value($photo_share_link_input_ele);

                    while ($old_share_url == $share_url && strlen($permission_check)) {
                        $first->pause(200);
                        $share_url = $first->value($photo_share_link_input_ele);
                    }

                    // getting the last imported row from database
                    $share = SharePhoto::orderBy('id', 'DESC')->first();
                    $this->assertNotNull($share);

                    // when the link is being generating in the backend
                    // the input box shows 'Generating Link'. So we need to
                    // pause the code for that and recheck
                    while (
                        $first->value("#photo-share-link") == 'Generating Link'
                    ) {
                        $first->pause(200);
                    }

                    // checking if photo-share-link is correctly generating
                    $first->assertValue(
                        "#photo-share-link",
                        route('share.show', ['key' => $share->share_key])
                    );
                    //$first->driver->switchTo()->window($first->driver->getWindowHandles()[1]);

                    //this elements should be visible
                    $ShouldBeVisible = ["img[src*='preview_photos/{$photo->file_name}']"];

                    // this elements should be invisble in the sharedPhoto Page
                    $ShouldBeMissing = [
                        "#open-share-modal-btn", # share-button on the top right
                        "#delete-modal-open-btn", # delete modal open button on the top right
                    ];


                    switch ($permission_check) {
                        case  "#share-view-info-checkbox":
                            array_push($ShouldBeVisible,  $this->view_info_btn_selector);
                            array_push($ShouldBeMissing, '#download-btn');
                            break;
                        case '#share-download-checkbox':
                            array_push($ShouldBeVisible, '#download-btn');
                            array_push($ShouldBeMissing,  $this->view_info_btn_selector);
                            break;
                        default:
                            array_push($ShouldBeMissing,  $this->view_info_btn_selector);
                            array_push($ShouldBeMissing, '#download-btn');
                    }
                    $second->visit($share_url);

                    // checking all the elements that should be visible
                    foreach ($ShouldBeVisible as $selector) {
                        $second->waitFor($selector)
                            ->assertVisible($selector);
                    }

                    // checking all the elements that should not be visible
                    foreach ($ShouldBeMissing as $selector) {
                        $second->assertMissing($selector);
                    }

                    //reloading the page for next permission
                    $first->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                        ->waitFor($share_modal_open_button_selector, 5)
                        ->assertVisible($share_modal_open_button_selector)
                        ->click($share_modal_open_button_selector);
                };
            }
        );
    }

    public function test_if_photo_can_be_downloaded_with_download_permission()
    {
        $this->browse(
            function (Browser $browser) {
                $photo = Photo::latest()->first();
                $share = SharePhoto::create([
                    'share_key' => bin2hex(random_bytes(16)),
                    'photo_id' => $photo->id,
                    'view_info' => false,
                    'download' => true
                ]);


                $photo_preview_selector = "img[src*='preview_photos/{$photo->file_name}']";
                $browser->visit($share->genUrl())
                    ->waitFor($photo_preview_selector, 5)
                    ->assertVisible($photo_preview_selector)
                    ->waitFor($this->download_btn_selector)
                    ->assertVisible($this->download_btn_selector);

                // getting the download url
                $downlaodUrl = $browser->attribute($this->download_btn_selector, 'href');
                $this->assertNotNull($downlaodUrl);

                // if the photo is correctly downloaded then
                // it will be redirected back to the previous image
                // view page. where download button will be visible
                $browser->visit($downlaodUrl)
                    ->waitFor($this->download_btn_selector)
                    ->assertVisible($this->download_btn_selector);
            }
        );
    }

    public function test_photo_details_are_correctly_visible_with_view_info_permission()
    {
        $this->browse(
            function (Browser $browser) {
                $photo = Photo::latest()->first();
                $share = SharePhoto::create([
                    'share_key' => bin2hex(random_bytes(16)),
                    'photo_id' => $photo->id,
                    'view_info' => true,
                    'download' => false
                ]);

                $browser->visit($share->genUrl())
                    ->waitFor($this->view_info_btn_selector)
                    ->assertVisible($this->view_info_btn_selector)
                    ->click($this->view_info_btn_selector)
                    ->assertValue(".js-editable__val", $photo->title);
            }
        );
    }
}
