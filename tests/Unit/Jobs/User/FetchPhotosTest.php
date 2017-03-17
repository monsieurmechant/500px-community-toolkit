<?php

namespace Tests\Unit\Jobs\User;


use App\Photo;
use Carbon\Carbon;
use Mockery as m;
use Tests\TestCase;
use App\Jobs\User\FetchPhotos;
use Tests\Unit\Stubs\FiveHundredPxPhotosCall;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class FetchPhotosTest extends TestCase
{

    use DatabaseMigrations;

    public function testItCallsTheUserPhotosApiEndpoint()
    {
        $this->disableModelEvents();
        $user = factory(\App\User::class)->create();

        $apiService = $this->buildApiServiceMock($user->getAttribute('id'));

        (new FetchPhotos($user->getAttribute('id')))->handle($apiService);
    }


    public function testItDoesNothingIfTheUserDoesNotExists()
    {
        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldNotReceive('authenticateClient');
        $apiService->shouldNotReceive('get');
        (new FetchPhotos(123))->handle($apiService);
    }

    public function testItAssociatesPhotosAccountsToTheUser()
    {
        $this->disableModelEvents();
        /** @var \App\User $user */
        $user = factory(\App\User::class)->create();
        $user->load('photos');
        $this->assertEquals(0, $user->photos->count());

        $nbPages = rand(1, 8);
        $rpp = rand(5, 20);

        $apiService = $this->buildApiServiceMock($user->getAttribute('id'), $nbPages, $rpp);
        (new FetchPhotos($user->getAttribute('id')))->handle($apiService);

        $user->load('photos');
        $this->assertEquals($nbPages * $rpp, $user->photos->count());
    }

    public function testItStopsFetchingPhotosOnceItReachesAPhotoAlreadyInDatabase()
    {
        $this->disableModelEvents();
        /** @var \App\User $user */
        $user = factory(\App\User::class)->create();

        $callResult = new FiveHundredPxPhotosCall(8, 1, 1, [3, 5], 'user_id');

        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldReceive('authenticateClient')->once()->withAnyArgs()->andReturnSelf();
        $apiService->shouldReceive('get')->once()->with(
            'photos',
            [
                'query' =>
                    [
                        'feature'        => 'user',
                        'user_id'        => $user->getAttribute('id'),
                        'sort'           => 'created_at',
                        'sort_direction' => 'desc',
                        'rpp'            => 100,
                        'page'           => 1,
                        'image_size'     => [3, 5],
                        'consumer_key'   => getenv('500PX_KEY'),
                    ]
            ]
        )->andReturn($callResult);

        \App\Photo::create([
            'id'          => $callResult->getPhoto(5)->getId(),
            'url'         => $callResult->getPhoto(5)->getImageUrl(),
            'url_full'    => $callResult->getPhoto(5)->getImageUrl(),
            'link'        => $callResult->getPhoto(5)->getUrl(),
            'name'        => $callResult->getPhoto(5)->getName() ?? null,
            'description' => $callResult->getPhoto(5)->getDescription() ?? null,
            'privacy'     => $callResult->getPhoto(5)->isPrivate(),
            'user_id'     => $user->getAttribute('id'),
            'posted_at'   => \Carbon\Carbon::createFromFormat(\DateTime::ATOM, $callResult->getPhoto(5)->getCreatedAt())
                ->setTimezone(config('app.timezone')),
        ]);

        (new FetchPhotos($user->getAttribute('id')))->handle($apiService);

        $user->load('photos');
        $this->assertEquals(6, $user->photos->count());


    }

    /**
     * @param int $userId
     * @param int $nbPages
     * @param int $rpp Result Per Page - Number of photos on each pages.
     * @return m\MockInterface
     */
    private
    function buildApiServiceMock(
        int $userId,
        int $nbPages = 1,
        int $rpp = 5
    ): m\MockInterface {
        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldReceive('authenticateClient')->once()->withAnyArgs()->andReturnSelf();
        for ($i = 1; $i <= $nbPages; $i++) {
            $apiService->shouldReceive('get')->once()->with(
                'photos',
                [
                    'query' =>
                        [
                            'feature'        => 'user',
                            'user_id'        => $userId,
                            'sort'           => 'created_at',
                            'sort_direction' => 'desc',
                            'rpp'            => 100,
                            'page'           => $i,
                            'image_size'     => [3, 5],
                            'consumer_key'   => getenv('500PX_KEY'),
                        ]
                ]
            )->andReturn(new FiveHundredPxPhotosCall($rpp, $i, $nbPages, [3, 5], 'user_id'));
        }
        return $apiService;
    }
}
