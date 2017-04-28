<?php
namespace Tests;


trait DisableModelEvents
{
    protected function disableModelEvents()
    {
        \App\User::unsetEventDispatcher();
        \App\Follower::unsetEventDispatcher();
        \App\Photo::unsetEventDispatcher();
        \App\Comment::unsetEventDispatcher();
    }
}