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
            $first->visit('/video/sister-spit-the-next-generation')
                ->assertPresent('.main');
//                ->assertVue('videoTitle', 'Sister Spit: The Next Generation', '@video-component')
//                ->assertPresent('.description')
//                ->assertPresent('.date')
//                ->assertTrue();
        });
    }
}
