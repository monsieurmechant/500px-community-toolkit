<?php

namespace Tests\Unit\Services\Api;

use \Mockery as m;
use Tests\TestCase;
use League\Fractal\Pagination\Cursor;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Testing as t;
use App\Services\Api\CreateApiDataFromCollection;

class CreateApiDataFromCollectionTest extends TestCase
{

    use t\DatabaseMigrations;

    /**
     * Make sure that the getTransformerFromCollection function
     * returns an instance of UserTransformer if we pass it
     * a Collection of User(s).
     *
     */
    public function testItReturnsAFractalTransformerFromACollection()
    {
        $users = factory(\App\User::class, 5)->make();

        $transformer = resolve(CreateApiDataFromCollection::class)->getTransformerFromCollection($users);

        $this->assertInstanceOf(UserTransformer::class, $transformer);
    }

    /**
     * Test that the class returns an array
     * that respects the dataArray API.
     *
     * @see http://fractal.thephpleague.com/serializers/#dataarrayserializer
     */
    public function testItReturnsAnArrayFromACollection()
    {
        $users = factory(\App\User::class, 5)->make();

        $transformer = resolve(CreateApiDataFromCollection::class)->with($users)->handle();
        $this->assertInternalType('array', $transformer);
        $this->assertArrayHasKey('data', $transformer);
    }

    /**
     * Test that the class returns an array if the collection
     * is only provided on the final handle() method.
     * This allows for a very short syntax when
     * includes or pages are not required.
     *
     */
    public function testItCanAcceptACollectionInTheHandleMethod()
    {
        $users = factory(\App\User::class, 5)->make();

        $transformer = resolve(CreateApiDataFromCollection::class)->handle($users);
        $this->assertInternalType('array', $transformer);
        $this->assertArrayHasKey('data', $transformer);
    }


    /**
     * Test that the class can accept a cursor object
     *
     * @see http://fractal.thephpleague.com/pagination/#using-cursors
     */
    public function testItAcceptsACursor()
    {
        $users = factory(\App\User::class, 5)->make();
        $cursor = new Cursor(5, 10, 15, 5);

        $transformer = resolve(CreateApiDataFromCollection::class)->with($users)
            ->cursor($cursor);

        $this->assertInstanceOf(Cursor::class, $transformer->getCursor());
    }

    /**
     * Makes sure that an exception is thrown when a user tries
     * to add a cursor without first setting the collection.
     */
    public function testItNeedsAnExistingResourceToAcceptACursor()
    {
        $cursor = new Cursor(5, 10, 15, 5);

        $this->expectException(\Exception::class);
        resolve(CreateApiDataFromCollection::class)->cursor($cursor);
    }

    /**
     * Makes sure that an exception is thrown when a user tries
     * to add includes without first setting the collection.
     */
    public function testItNeedsAnExistingResourceToAcceptIncludes()
    {
        $this->expectException(\Exception::class);
        resolve(CreateApiDataFromCollection::class)
            ->includes('followers');
    }
}
