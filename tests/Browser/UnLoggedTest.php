<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class UnLoggedTest extends DuskTestCase
{
    /**
     * Tests that a user visiting the homepage
     * is redirected to the login page
     * when not authenticated.
     *
     * @return void
     */
    public function testItSeesTheLoginButtonWhenNotAuthenticated()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Login With 500px');
        });
    }
}
