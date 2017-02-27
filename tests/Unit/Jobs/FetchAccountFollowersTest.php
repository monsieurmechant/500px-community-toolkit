<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 27/02/2017
 * Time: 11:49
 */

namespace Tests\Unit\Jobs;


use App\Account;
use Carbon\Carbon;
use Mockery as m;
use Tests\TestCase;
use App\Jobs\FetchAccountFollowers;
use Tests\Unit\Stubs\FiveHundredPxUsersFollowersCall;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class FetchAccountFollowersTest extends TestCase
{

    use DatabaseMigrations;

    public function testItCallsTheUserFollowersApiEndpoint()
    {
        \App\User::unsetEventDispatcher();
        $user = factory(\App\User::class)->create();

        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldReceive('authenticateClient')->once()->withAnyArgs()->andReturnSelf();
        $apiService->shouldReceive('get')->once()->with(
            'users/' . $user->getAttribute('id') . '/followers',
            ['query' => ['rpp' => 100, 'page' => 1]]
        )->andReturn(new FiveHundredPxUsersFollowersCall(5, 1, 1));

        (new FetchAccountFollowers($user->getAttribute('id')))->handle($apiService);
    }


    public function testItDoesNothingIfTheUserDoesNotExists()
    {
        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldNotReceive('authenticateClient');
        $apiService->shouldNotReceive('get');
        (new FetchAccountFollowers(123))->handle($apiService);
    }

    public function testItAssociatesFollowersAccountsToTheUser()
    {
        \App\User::unsetEventDispatcher();
        $user = factory(\App\User::class)->create();

        $this->assertEquals(0, count($user->followers));

        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldReceive('authenticateClient')->once()->withAnyArgs()->andReturnSelf();
        $pages = 5;
        for ($i = 1; $i <= $pages; $i++) {
            $apiService->shouldReceive('get')->with(
                'users/' . $user->getAttribute('id') . '/followers',
                ['query' => ['rpp' => 100, 'page' => $i]]
            )->andReturn(new FiveHundredPxUsersFollowersCall(5, $i, $pages));
        }
        (new FetchAccountFollowers($user->getAttribute('id')))->handle($apiService);

        $user->load('followers');
        $this->assertEquals(25, count($user->followers));
    }

    public function testItOnlyUpdatesExistingFollowersThatHaveNotBeenUpdatedToday()
    {
        $callResponse = new FiveHundredPxUsersFollowersCall(5, 1, 1);

        Account::create([
            'id'        => $callResponse->followers[0]->id,
            'username'  => $callResponse->followers[0]->username ?? null,
            'name'      => $callResponse->followers[0]->fullname ?? null,
            'avatar'    => $callResponse->followers[0]->userpic_url ?? null,
            'followers' => $callResponse->followers[0]->followers_count ?? 0,
            'affection' => $callResponse->followers[0]->affection ?? 0,
        ]);

        Account::create([
            'id'        => $callResponse->followers[1]->id,
            'username'  => $callResponse->followers[1]->username ?? null,
            'name'      => $callResponse->followers[1]->fullname ?? null,
            'avatar'    => $callResponse->followers[1]->userpic_url ?? null,
            'followers' => $callResponse->followers[1]->followers_count ?? 0,
            'affection' => $callResponse->followers[1]->affection ?? 0,
        ]);

        $account = Account::find($callResponse->followers[0]->id);
        $this->assertEquals($callResponse->followers[0]->followers_count, $account->getAttribute('followers'));

        $account->setAttribute('updated_at', Carbon::yesterday()->subDay()->toDateString())
            ->save();

        $callResponse->followers[0]->setFollowersCount(12345678);
        $callResponse->followers[1]->setFollowersCount(87654321);

        \App\User::unsetEventDispatcher();
        $user = factory(\App\User::class)->create();

        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldReceive('authenticateClient')->once()->withAnyArgs()->andReturnSelf();
        $apiService->shouldReceive('get')->once()->with(
            'users/' . $user->getAttribute('id') . '/followers',
            ['query' => ['rpp' => 100, 'page' => 1]]
        )->andReturn($callResponse);

        (new FetchAccountFollowers($user->getAttribute('id')))->handle($apiService);

        $this->assertEquals(12345678, Account::find($callResponse->followers[0]->id)->getAttribute('followers'));
        $this->assertNotEquals(87654321, Account::find($callResponse->followers[1]->id)->getAttribute('followers'));
    }

    public function testItZeroesNegativeAffectionAndFollowers()
    {
        $callResponse = new FiveHundredPxUsersFollowersCall(5, 1, 1);
        $callResponse->followers[0]->setFollowersCount(-2);
        $callResponse->followers[1]->setAffection(-2);

        \App\User::unsetEventDispatcher();
        $user = factory(\App\User::class)->create();

        $apiService = m::mock(\App\Http\Services\FiveHundredPxService::class);
        $apiService->shouldReceive('authenticateClient')->once()->withAnyArgs()->andReturnSelf();
        $apiService->shouldReceive('get')->once()->with(
            'users/' . $user->getAttribute('id') . '/followers',
            ['query' => ['rpp' => 100, 'page' => 1]]
        )->andReturn($callResponse);

        (new FetchAccountFollowers($user->getAttribute('id')))->handle($apiService);

        $this->assertEquals(0, Account::find($callResponse->followers[0]->id)->getAttribute('followers'));
        $this->assertEquals(0, Account::find($callResponse->followers[1]->id)->getAttribute('affection'));
    }
}
