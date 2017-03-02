<?php

namespace App\Http\Controllers\InternalApi;

use App\Photo;
use \App\Http\Requests\Photos as R;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal\Pagination\Cursor;

class PhotosController extends InternalApiController
{

    /**
     * @param R\GetCollectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(R\GetCollectionRequest $request)
    {
        $photos = Photo::where('user_id', '=', $request->user()->getAttribute('id'));
        if ($request->has('cursor')) {
            $photos->where('posted_at', '<=', base64_decode($request->query('cursor')));
        }

        if ($request->query('unread_comments', false)) {
            $photos->onlyWithNewComments();
        }

        $photos = $photos->orderBy('posted_at', 'DSC')->take(50)->get();

        $nextCursor = '';
        if ($photos->count() >= 50) {
            $nextCursor = base64_encode($photos->last()->getAttribute('posted_at'));
        }
        $cursor = new Cursor(
            $request->query('cursor', ''),
            $request->query('previous', ''),
            $nextCursor,
            $photos->count()
        );

        return $this->returnCollection($photos, 200, $request->query('includes'), $cursor);
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
            $photo = \App\Photo::findOrFail($id);
            return $this->returnItem($photo, 200, $request->query('includes'));
        } catch (ModelNotFoundException $e) {
            return $this->returnError(404, 'This account does not exist.', 'ModelNotFoundException');
        }

    }
}
