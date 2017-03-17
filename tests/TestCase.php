<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function disableModelEvents()
    {
        \App\User::unsetEventDispatcher();
        \App\Follower::unsetEventDispatcher();
        \App\Photo::unsetEventDispatcher();
        \App\Comment::unsetEventDispatcher();
    }
}
