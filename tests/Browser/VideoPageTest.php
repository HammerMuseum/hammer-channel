<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

/**
 * Class VideoPageTest
 *
 * @package Tests\Browser
 */
class VideoPageTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testVideoPage()
    {
        $this->browse(function ($first) {
            $first->visit('/video/1')
                ->assertPresent('video')
                ->assertVue('videoTitle', 'Mark Bradford\'s Finding Barry', '@video-component')
                ->assertPresent('.description')
                ->assertPresent('.date')
                ->assertPresent('.breadcrumb');
        });
    }
}
