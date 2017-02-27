<?php

namespace Tests\Unit\Services\Api;

use Tests\TestCase;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Testing as t;
use App\Services\Api\CreateApiDataFromModel;

class CreateApiDataFromModelTest extends TestCase
{

    use t\DatabaseMigrations;

    /**
     * Make sure that the getTransformerFromModel function
     * returns an instance of UserTransformer if we pass it
     * a Model of User(s).
     *
     */
    public function testItReturnsAFractalTransformerFromAModel()
    {
        $users = factory(\App\User::class)->make();

        $transformer = resolve(CreateApiDataFromModel::class)->getTransformerFromModel($users);

        $this->assertInstanceOf(UserTransformer::class, $transformer);
    }

    /**
     * Test that the class returns an array
     * that respects the dataArray API.
     *
     * @see http://fractal.thephpleague.com/serializers/#dataarrayserializer
     */
    public function testItReturnsAnArrayFromAModel()
    {
        $users = factory(\App\User::class)->make();

        $transformer = resolve(CreateApiDataFromModel::class)->with($users)->handle();
        $this->assertInternalType('array', $transformer);
        $this->assertArrayHasKey('data', $transformer);
    }

    /**
     * Test that the class returns an array if the model
     * is only provided on the final handle() method.
     * This allows for a very short syntax when
     * includes or pages are not required.
     *
     */
    public function testItCanAcceptAModelInTheHandleMethod()
    {
        $users = factory(\App\User::class)->make();

        $transformer = resolve(CreateApiDataFromModel::class)->handle($users);
        $this->assertInternalType('array', $transformer);
        $this->assertArrayHasKey('data', $transformer);
    }

}
