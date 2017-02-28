<?php

namespace App\Http\Controllers\InternalApi;

use App\Jobs\GetFullFollowerProfile;
use App\Http\Requests\GetFollowersRequest;
use App\Http\Requests\GetFollowerProfileRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FollowersController extends InternalApiController
{

    public function index(GetFollowersRequest $request)
    {
        $sort = $request->input('order-by', 'followers');
        $skip = (int)$request->input('skip', 0);
        $limit = (int)$request->input('limit', 50);

        $user = \App\User::with([
            'followers' => function ($query) use ($sort, $skip, $limit) {
                $query->orderBy($sort, 'desc')
                    ->skip($skip)
                    ->take($limit);
            }
        ])->find($request->user()->getAttribute('id'));
        return $this->returnCollection($user->followers, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param GetFollowerProfileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, GetFollowerProfileRequest $request)
    {
        if (!(bool)$request->input('full')) {
            try {
                $follower = \App\Follower::findOrFail($id);
                return $this->returnItem($follower, 200);
            } catch (ModelNotFoundException $e) {
                return $this->returnError(404, 'This account does not exist.', 'ModelNotFoundException');
            }
        }

        return response()->json(
            resolve(\App\Http\Services\Requests\GetFullFollowerProfile::class)->handle($id, $request->user())
        , 200);

    }
}
