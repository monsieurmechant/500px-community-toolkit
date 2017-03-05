<?php
namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing as t;

class CommentPolicyTest extends TestCase
{

    use t\DatabaseMigrations;

    public function testOnlyTheOwnerOfThePhotoCanUpdateAComment()
    {
        $this->disableModelEvents();
        $user = factory(\App\User::class)->create();
        $photo = factory(\App\Photo::class)->create([
            'user_id' => $user->getAttribute('id'),
        ]);
        $comment = factory(\App\Comment::class)->create([
            'photo_id' => $photo->getAttribute('id'),
        ]);

        $policy = new CommentPolicy();

        $this->assertTrue($policy->update($user, $comment));

        $this->assertFalse($policy->update(factory(\App\User::class)->make(), $comment));

    }

    public function testOnlyTheOwnerOfThePhotoCanDeleteAComment()
    {
        $this->disableModelEvents();
        $user = factory(\App\User::class)->create();
        $photo = factory(\App\Photo::class)->create([
            'user_id' => $user->getAttribute('id'),
        ]);
        $comment = factory(\App\Comment::class)->create([
            'photo_id' => $photo->getAttribute('id'),
        ]);

        $policy = new CommentPolicy();

        $this->assertTrue($policy->delete($user, $comment));

        $this->assertFalse($policy->delete(factory(\App\User::class)->make(), $comment));

    }
}
