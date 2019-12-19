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
                ->assertPresent('.title')
                ->assertPresent('.description')
                ->assertPresent('.date')
                ->assertPresent('video')
                ->assertPresent('.breadcrumb');
        });
    }
}
