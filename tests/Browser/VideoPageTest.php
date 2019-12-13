<?php

namespace Tests\Browser;

use App\FakeApi;
use Tests\CreatesApplication;
use Tests\DuskTestCase;
use App\Api;
use Mockery;

class VideoPageTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testVideoPage()
    {
        $this->browse(function ($first) {
            $first->visit('/video/1')
                ->assertPresent('.title')
                ->assertPresent('.description')
                ->assertPresent('.date')
                ->assertPresent('video');
        });
    }
}
