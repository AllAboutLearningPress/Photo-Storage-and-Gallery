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
                $show_details_button_selector = ".image-view__show-details";
                // checking for all the buttons
                $first->assertVisible("button[data-bs-target='#shareModal']") // share cancel button
                    ->assertVisible($open_share_link_btn)
                    ->assertVisible("#copy-share-link") // link copy button
                    ->assertVisible($photo_share_link_input_ele);

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

                    $share = SharePhoto::orderBy('id', 'DESC')->first();
                    $this->assertNotNull($share);
                    while (
                        $first->value("#photo-share-link") == 'Generating Link'
                    ) {
                        $first->pause(200);
                    }
                    $first->assertValue(
                        "#photo-share-link",
                        route('share.show', ['key' => $share->share_key])
                    ); //->click($open_share_link_btn);
                    //$first->driver->switchTo()->window($first->driver->getWindowHandles()[1]);
                    $ShouldBeVisible = ["img[src*='preview_photos/{$photo->file_name}']"];

                    // this buttons should be invisble in the sharedPhoto Page
                    $ShouldBeMissing = [
                        "#open-share-modal-btn", # share-button on the top right
                        "#delete-modal-open-btn", # delete modal open button on the top right
                    ];


                    switch ($permission_check) {
                        case  "#share-view-info-checkbox":
                            array_push($ShouldBeVisible,  $show_details_button_selector);
                            array_push($ShouldBeMissing, '#download-btn');
                            break;
                        case '#share-download-checkbox':
                            array_push($ShouldBeVisible, '#download-btn');
                            array_push($ShouldBeMissing,  $show_details_button_selector);
                            break;
                        default:
                            array_push($ShouldBeMissing,  $show_details_button_selector);
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

                    $first->visitRoute('photos.show', ['id' => $photo->id, 'slug' => $photo->slug])
                        ->waitFor($share_modal_open_button_selector, 5)
                        ->assertVisible($share_modal_open_button_selector)
                        ->click($share_modal_open_button_selector);
                };
            }
        );
    }

    public function test_sharable_link_with_only_view_info_permission()
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
                // checking for all the buttons
                $first->assertVisible("button[data-bs-target='#shareModal']") // share cancel button
                    ->assertVisible($open_share_link_btn)
                    ->assertVisible("#copy-share-link") // link copy button
                    ->assertVisible($photo_share_link_input_ele);


                // checking if share link input element is correct
                $share = SharePhoto::latest()->first();
                $this->assertNotNull($share);
                $first->assertValue(
                    "#photo-share-link",
                    route('share.show', ['key' => $share->share_key])
                ); //->click($open_share_link_btn);
                $share_url = $first->value($photo_share_link_input_ele);
                //$first->driver->switchTo()->window($first->driver->getWindowHandles()[1]);
                $second->visit($share_url)
                    ->assertVisible("img[src*='preview_photos/{$photo->file_name}']")
                    ->assertMissing("#open-share-modal-btn") // share button shouldnt be visible
                    ->assertMissing("#download-btn") // download button shoulnt be visible
                    ->assertMissing("#delete-modal-open-btn") // delete button shouldnt be visible
                    ->assertMissing("#show-details-btn"); // show-details button shouldnt be visible
                // $second->assertSee('asfsa');
                // generate share link with view-info permission
                $first->assertVisible("#share-view-info-checkbox")
                    ->assertSee("Can View Info")
                    ->assertVisible("#share-download-checkbox")
                    ->assertSee('Can Download')
                    ->check("#share-view-info-checkbox")
                    ->pause(25465464646);

                $first->uncheck("#share-download-checkbox");
                $share_url = $first->value($photo_share_link_input_ele);

                $second->visit($share_url)
                    ->assertVisible("img[src*='preview_photos/{$photo->file_name}']")
                    ->assertMissing("#open-share-modal-btn") // share button shouldnt be visible
                    ->assertMissing("#download-btn") // download button shoulnt be visible
                    ->assertMissing("#delete-modal-open-btn") // delete button shouldnt be visible
                    ->assertVisible("#show-details-btn"); // show-details button shouldnt be visible
            }
        );
    }
}
