<?php

namespace Tests\Unit;


use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class UserTest extends TestCase
{

    use DatabaseMigrations;

    /** @var User $user */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->disableModelEvents();
        $this->user = factory(User::class)->create(['quick_replies' => []]);
    }

    public function testItCastsQuickRepliesToAnArray()
    {
        $this->assertInternalType('array', $this->user->getAttribute('quick_replies'));
    }

    public function testItAcceptsAnArrayForQuickReplies()
    {
        $this->assertEquals(0, count($this->user->getAttribute('quick_replies')));

        $this->user->addQuickReply('Foo');
        $this->user->save();

        $this->assertEquals(1, count($this->user->getAttribute('quick_replies')));

        $this->user->addQuickReply('Bar');
        $this->user->save();

        $this->assertEquals(2, count($this->user->getAttribute('quick_replies')));
        $this->assertContains('Foo', $this->user->getAttribute('quick_replies'));
        $this->assertContains('Bar', $this->user->getAttribute('quick_replies'));

        $this->user->setAttribute('quick_replies', ['Fizz']);
        $this->user->save();

        $this->assertEquals(1, count($this->user->getAttribute('quick_replies')));
        $this->assertContains('Fizz', $this->user->getAttribute('quick_replies'));
        $this->assertNotContains('Foo', $this->user->getAttribute('quick_replies'));
        $this->assertNotContains('Bar', $this->user->getAttribute('quick_replies'));
    }

    public function testItCanAddAndRemoveQuickReplies()
    {
        $this->assertEquals(0, count($this->user->getAttribute('quick_replies')));

        $this->user->addQuickReply('Foo');
        $this->user->save();

        $this->assertEquals(1, count($this->user->getAttribute('quick_replies')));

        $this->user->addQuickReply('Bar');
        $this->user->addQuickReply('Bizz');
        $this->user->addQuickReply('Buzz');
        $this->user->save();

        $this->assertEquals(4, count($this->user->getAttribute('quick_replies')));
        $this->assertEquals(['Foo', 'Bar', 'Bizz', 'Buzz'], $this->user->getAttribute('quick_replies'));

        $this->user->removeQuickReply('Bar');
        $this->user->removeQuickReply('Buzz');
        $this->user->save();

        $this->assertEquals(2, count($this->user->getAttribute('quick_replies')));
        $this->assertContains('Foo', $this->user->getAttribute('quick_replies'));
        $this->assertContains('Bizz', $this->user->getAttribute('quick_replies'));
        $this->assertNotContains('Bar', $this->user->getAttribute('quick_replies'));
        $this->assertNotContains('Buzz', $this->user->getAttribute('quick_replies'));
    }
}
