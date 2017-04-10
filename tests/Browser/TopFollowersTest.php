<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Database\Eloquent\Collection;

class TopFollowersTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * Tests that when visiting the
     * top followers page, the
     * user sees just that.
     *
     * @return void
     */
    public function testItSeesTheListOfFollowers()
    {
        $user = factory(\App\User::class)->create();
        $followers = $this->createFollowers($user, 50);

        $this->browse(function (Browser $browser) use ($user, $followers) {
            $browser->loginAs($user)
                ->visit('/app/#/followers');

            for ($i = 0; $i < 10; $i++) {
                $browser->assertSee($followers->random()->getAttribute('name'));
            }
        });
    }

    /**
     * @param \App\User $user
     * @param int $quantity
     * @param array $attributes
     * @return Collection
     */
    private function createFollowers(\App\User $user, int $quantity = 10, array $attributes = []): Collection
    {
        $this->disableModelEvents();
        $followers = factory(\App\Follower::class, $quantity)->create($attributes);

        $user->followers()->sync($followers->pluck('id'));

        return $followers;

    }
}
