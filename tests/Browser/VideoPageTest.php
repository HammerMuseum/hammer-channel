<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Api;
use App\ApiInterface;
use App\TestApi;

class VideoPageTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testVideoPage()
    {
        // Test if an error message shows when the page is empty
        $this->browse(function (Browser $browser) {
            $browser->visit('/video/1')
                ->assertPresent('.message');
        });

        $this->browse(function (Browser $browser) {//
            $api = $this->app->make('App\TestApi');
//            // Check that the controller/template spits out the right thing.
            $browser->visit('/video/1')
                ->assertPresent('.main')
                ->assertPresent('.title')
                ->assertPresent('.description')
                ->assertPresent('video')
                ->assertPresent('.date')
                ->assertPresent('.duration');
        });
    }
}
