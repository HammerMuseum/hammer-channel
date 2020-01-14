<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class TopicListingTest
 * @package Tests\Browser
 */
class TopicListingTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testTopicListingPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/topics/art')
                ->assertSee('Art')
                ->assertPresent('.listing')
                ->assertPresent('.title')
                ->assertPresent('.result-grid');
        });
    }
}
