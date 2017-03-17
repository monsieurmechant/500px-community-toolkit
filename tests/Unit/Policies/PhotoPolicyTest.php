<?php
namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Policies\PhotoPolicy;
use Illuminate\Foundation\Testing as t;

class PhotoPolicyTest extends TestCase
{

    use t\DatabaseMigrations;

    public function testOnlyTheOwnerOfThePhotoCanUpdateAPhoto()
    {
        $this->disableModelEvents();
        $user = factory(\App\User::class)->create();
        $photo = factory(\App\Photo::class)->create([
            'user_id' => $user->getAttribute('id'),
        ]);

        $policy = new PhotoPolicy();

        $this->assertTrue($policy->update($user, $photo));

        $this->assertFalse($policy->update(factory(\App\User::class)->make(), $photo));

    }

    public function testOnlyTheOwnerOfThePhotoCanDeleteAPhoto()
    {
        $this->disableModelEvents();
        $user = factory(\App\User::class)->create();
        $photo = factory(\App\Photo::class)->create([
            'user_id' => $user->getAttribute('id'),
        ]);

        $policy = new PhotoPolicy();

        $this->assertTrue($policy->delete($user, $photo));

        $this->assertFalse($policy->delete(factory(\App\User::class)->make(), $photo));

    }
}
