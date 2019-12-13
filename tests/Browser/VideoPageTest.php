<?php

namespace Tests\Browser;

use App\FakeApi;
use Tests\CreatesApplication;
use Tests\DuskTestCase;
use App\Api;
use Mockery;

class VideoPageTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setup();

    }

    /**
     * @throws \Throwable
     */
    public function testVideoPage()
    {
//        $this->app->bind(\App\Api::class, function() {
//            return new FakeApi();
//        });
        $this->browse(function ($first) {
            $first->visit('/video/1')
                ->assertPresent('.title');
        });
    }

    public function getJsonData()
    {
        return '{
        "asset_id": 204,
  "title": "Mark Bradford\'s Finding Barry",
  "description": "Since 1999, the museum has commissioned artists to create site-specific projects for the Hammer Museum Lobby Wall. Mark Bradford created Finding Barry by excavating through 100 layers of paint on the wall. Barry refers to Barry McGee, one of the earliest Lobby Wall artists. Bradford\'s rendering of a U.S. map shows the population of people diagnosed with AIDS per 100,000 in each state as of 2009.",
  "thumbnail_url": null,
  "video_url": "https://trial10-8.assetbank-server.com/assetbank-trial10/rest/assets/206/content",
  "date_recorded": "2019-12-09 12:05:57",
  "duration": "00:01:43",
  "created_at": "2019-12-09 11:52:24",
  "updated_at": "2019-12-09 11:52:24"
}';
    }
}
