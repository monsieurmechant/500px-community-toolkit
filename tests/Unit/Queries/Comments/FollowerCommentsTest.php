<?php
namespace Tests\Unit\Queries\Comments;


use Tests\TestCase;
use Illuminate\Foundation\Testing as t;
use App\Queries\Comments\GetByFollower;

class FollowerCommentsTest extends TestCase
{

    use t\DatabaseMigrations;

    public function testItFetchesAllTheCommentsLeftByAFollowerToAUser()
    {
        $this->disableModelEvents();

        $user = factory(\App\User::class)->create();
        $follower = factory(\App\Follower::class)->create();

        // generate 40 comments, by the same follower, on 4 different photos owned by the same user.
        for ($i = 0; $i <4; $i++) {
            $photos = factory(\App\Photo::class)->create([
                'user_id' => $user->getAttribute('id'),
            ]);

            factory(\App\Comment::class, 10)->create([
                'follower_id' => $follower->getAttribute('id'),
                'photo_id'    => $photos->getAttribute('id')
            ]);
        }

        // Generate an extra 15 random comments.
        factory(\App\Comment::class, 15)->create();

        $comments = (new GetByFollower())->get($follower, $user);

        $this->assertEquals($comments->count(), 40);
        $this->assertEquals(\App\Comment::all()->count(), 55);


    }
}
