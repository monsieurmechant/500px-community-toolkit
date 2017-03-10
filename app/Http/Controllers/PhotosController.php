<?php

namespace App\Http\Controllers;

use Redis;
use App\Jobs\User as J;
use Illuminate\Http\Request;

class PhotosController extends Controller
{

    public function index(Request $request)
    {
        if (!Redis::get('photos.refreshed.recently.' . $request->user()->getAttribute('id'))) {
            $this->dispatch(new J\FetchPhotos($request->user()->getAttribute('id')));
            $this->dispatch(new J\FetchNewComments($request->user()));
            Redis::setex('photos.refreshed.recently.' . $request->user()->getAttribute('id'), 15 * 60, true);
        }

        return view('photos.index');
    }
}
