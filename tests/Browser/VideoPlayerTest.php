<?php

namespace Tests\Browser;

use App\Api;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class VideoPlayerTest
 * @package Tests\Browser
 */
class VideoPlayerTest extends DuskTestCase
{
    /**
    * Test video player attributes
     *
     * @throws \Throwable
     */
    public function testVideoPlayer()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/video/1')
                // Checks if the video source has loaded the provided URL
                ->assertSourceHas(
                    '<source src="https://trial10-8.assetbank-server.com/assetbank-trial10/rest/assets/206/content" type="video/mp4">'
                )
                // Checks if the video JS library has been applied to the video player
                ->assertSourceHas('<video id="hammer-video-player_html5_api"');
        });
    }
}
