<?php

namespace App\Http\Controllers\InternalApi;

use Cache;
use \App\Http\Requests\Comments as R;
use League\Fractal\Pagination\Cursor;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentsController extends InternalApiController
{

    /**
     * @param R\GetCollectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(R\GetCollectionRequest $request)
    {

        if ($request->query('follower_id', null)) {
            return Cache::tags('follower_comments_' . $request->query('follower_id'))
                ->rememberForever(
                    'follower_comments_response_' . $request->query('follower_id') . '_' . $request->user()->getAttribute('id'),
                    function () use ($request) {
                        $comments = $request->user()->load([
                            'comments' => function ($query) use ($request) {
                                $query->where('follower_id', '=', $request->query('follower_id', null))
                                    ->orderBy('posted_at', 'DSC')->take(50);
                            },
                            'comments.follower',
                            'comments.photo'
                        ])->comments;
                        return $this->returnCollection($comments, 200, ['photo']);
                    }
                );
        }
        $comments = $request->user()->load([
            'comments' => function ($query) use ($request) {
                ;
                if ($request->has('cursor')) {
                    $query->where('posted_at', '<=', base64_decode($request->query('cursor')));
                }
                $query->orderBy('posted_at', 'DSC')->take(50);
            },
            'comments.follower',
            'comments.photo'
        ])->comments;
        $nextCursor = '';
        if ($comments->count() >= 50) {
            $nextCursor = base64_encode($comments->last()->getAttribute('posted_at'));
        }
        $cursor = new Cursor(
            $request->query('cursor', ''),
            $request->query('previous', ''),
            $nextCursor,
            $comments->count()
        );

        return $this->returnCollection($comments, 200, null, $cursor);
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
        } catch (ModelNotFoundException $e) {
            return $this->returnError(404, 'This comment does not exist.', 'ModelNotFoundException');
        }

    }
}
