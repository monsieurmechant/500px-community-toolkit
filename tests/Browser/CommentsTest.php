<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Database\Eloquent\Collection;

class CommentsTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->disableModelEvents();
    }

    /**
     * Tests that when visiting the
     * comments page, the user
     * sees just that.
     *
     * @return void
     */
    public function testItSeesTheListOfUnreadComments()
    {
        $user = factory(\App\User::class)->create();
        $photos = $this->createPhotos($user, 1);
        $unreadComments = $this->createComments($photos->first(), 6, ['read' => false]);
        $readComment = $this->createComments($photos->first(), 1, ['read' => true, 'body' => 'I am not visible']);

        $this->browse(function (Browser $browser) use ($user, $photos, $unreadComments, $readComment) {
            $browser->loginAs($user)
                ->visit('/app/#/comments')
                ->waitFor('.comments-manager', 3)
                ->assertSee($photos->first()->getAttribute('name'))
                ->assertDontSee($readComment->first()->getAttribute('body'));

            foreach ($unreadComments as $comment) {
                $browser->assertSee($comment->getAttribute('body'));
            }
        });
    }

    /**
     * Tests that a user can
     * mark a comment
     * as read
     *
     * @return void
     */
    public function testThatACommentCanBeMarkerRead()
    {
        $user = factory(\App\User::class)->create();
        $photos = $this->createPhotos($user, 1);
        $comments = $this->createComments($photos->first(), 1, ['read' => false]);

        $this->browse(function (Browser $browser) use ($user, $photos, $comments) {
            $browser->loginAs($user)
                ->visit('/app/#/comments')
                ->waitFor('.comments-manager', 3)
                ->assertSee($comments->first()->getAttribute('body'))
                ->click('.mark-read')
                ->waitUntilMissing('.mark-read', 1)
                ->assertDontSee($comments->first()->getAttribute('body'));
        });
    }

    /**
     * Tests that a user can
     * mark a thread
     * as read.
     *
     * @return void
     */
    public function testThatAThreadCanBeMarkerRead()
    {
        $user = factory(\App\User::class)->create();
        $photo = $this->createPhotos($user, 1)->first();
        $this->createComments($photo, 5, ['read' => false]);

        $this->browse(function (Browser $browser) use ($user, $photo) {
            $browser->loginAs($user)
                ->visit('/app/#/comments')
                ->waitFor('.comments-manager', 3)
                ->assertSee($photo->getAttribute('name'))
                ->click('.read-all-button')
                ->waitUntilMissing('.read-all-button', 1)
                ->assertDontSee($photo->getAttribute('name'));
        });
    }

    /**
     * @param \App\User $user
     * @param int $quantity
     * @param array $attributes
     * @return Collection
     */
    private function createPhotos(\App\User $user, int $quantity = 10, array $attributes = []): Collection
    {
        if (!array_key_exists('user_id', $attributes)) {
            $attributes = $attributes + ['user_id' => $user->getAttribute('id')];
        }

        $photos = factory(\App\Photo::class, $quantity)->create($attributes);

        return $photos;

    }

    /**
     * @param \App\Photo $photo
     * @param int $quantity
     * @param array $attributes
     * @return Collection
     */
    private function createComments(\App\Photo $photo, int $quantity = 10, array $attributes = []): Collection
    {
        if (!array_key_exists('photo_id', $attributes)) {
            $attributes = $attributes + ['photo_id' => $photo->getAttribute('id')];
        }

        $comments = factory(\App\Comment::class, $quantity)->create($attributes);

        return $comments;
    }
}
