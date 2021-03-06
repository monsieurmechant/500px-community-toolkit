<?php

namespace App\Http\Controllers\InternalApi;

use App\Events\UserRequestedPhotos;
use App\Photo;
use \App\Http\Requests\Photos as R;
use Carbon\Carbon;
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
        event(new UserRequestedPhotos($request->user()));

        $photos = Photo::where('user_id', '=', $request->user()->getAttribute('id'));
        if ($request->has('cursor')) {
            $photos->where('posted_at', '<=', base64_decode($request->query('cursor')));
        }

        if ($request->has('from')) {
            $photos->where('posted_at', '<=', Carbon::parse($request->query('from'))->toDateTimeString());
        }

        if ($request->has('to')) {
            $photos->where('posted_at', '>=', Carbon::parse($request->query('to'))->toDateTimeString());
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
            return $this->returnError(404, 'This photo does not exist.', 'ModelNotFoundException');
        }
    }

    /**
     * Update the specified resource.
     * For the time being the only
     * interaction is marking all
     * its comments as read.
     *
     * @param int $id
     * @param R\UpdateItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, R\UpdateItemRequest $request)
    {
        try {
            $photo = \App\Photo::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->returnError(404, 'This photo does not exist.', 'ModelNotFoundException');
        }

        if ($request->has('read_comments')) {
            \App\Comment::where('photo_id', $id)
                ->update(['read' => 1]);
        }

        return $this->returnItem($photo, 201, $request->query('includes'));
    }
}
