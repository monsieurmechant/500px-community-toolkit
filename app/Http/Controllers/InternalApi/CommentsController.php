<?php

namespace App\Http\Controllers\InternalApi;

use Cache;
use \App\Http\Requests\Comments as R;
use Illuminate\Database\Eloquent as E;

class CommentsController extends InternalApiController
{

    /**
     * @param R\GetCollectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(R\GetCollectionRequest $request)
    {

        return Cache::tags('follower_comments_' . $request->query('follower_id'))
            ->rememberForever(
                'follower_comments_response_' . $request->query('follower_id') . '_' . $request->user()->getAttribute('id'),
                function () use ($request) {
                    $comments = (new \App\Queries\Comments\GetByFollower())
                        ->get(\App\Follower::find($request->query('follower_id')), $request->user());
                    return $this->returnCollection($comments, 200, ['photo']);
                }
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param R\GetItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, R\GetItemRequest $request)
    {
        try {
            $comment = \App\Comment::findOrFail($id);
            return $this->returnItem($comment, 200, $request->query('includes'));
        } catch (E\ModelNotFoundException $e) {
            return $this->returnError(404, 'This comment does not exist.', 'ModelNotFoundException');
        }
    }

    /**
     * Update the specified resource.
     *
     * @param int $id
     * @param R\UpdateItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, R\UpdateItemRequest $request)
    {
        try {
            $comment = \App\Comment::findOrFail($id);
            $comment->load(['photo']);
        } catch (E\ModelNotFoundException $e) {
            return $this->returnError(404, 'This comment does not exist.', 'ModelNotFoundException');
        }

        if (!$request->has('read')) {
            return $this->returnItem($comment, 201, ['photo']);
        }

        $comment->setAttribute('read', $request->input('read'));
        $modifiedComment = $comment;

        try {
            $comment->save();
        } catch (\Exception $e) {
            return $this->returnError(400, 'We could not process your request.');
        }


        return $this->returnItem($modifiedComment, 201, ['photo']);
    }
}
