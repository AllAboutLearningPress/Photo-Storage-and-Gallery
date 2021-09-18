<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotoTagTest extends UploadTest
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testAddTag()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('photos.show', [
                'id' => $this->uploadedPhoto->id,
                'slug' => $this->uploadedPhoto->slug
            ]))->pause(100000);
        });
    }
}
