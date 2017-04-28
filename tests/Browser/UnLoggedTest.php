<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnLoggedTest extends DuskTestCase
{

    use DatabaseMigrations;

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
                ->assertPathIs('/login')
                ->assertSee('Login With 500px');
        });
    }

    /**
     * Tests that a logged-in user using the SPA
     * is redirected to the login page when
     * he logs out in another window.
     *
     * @return void
     */
    public function testItSeesTheLoginButtonWhenNotAuthenticatedInAppPage()
    {
        $this->disableModelEvents();
        $user = factory(\App\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/app/#/followers')
                ->pause(1000)
                ->logout()
                ->visit('/app/#/comments')
                ->assertPathIs('/login')
                ->assertSee('Login With 500px');
        });
    }
}
