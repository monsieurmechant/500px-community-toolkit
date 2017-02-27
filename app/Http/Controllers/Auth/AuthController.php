<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{

    /**
     * Redirect the user to the 500px authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::with('500px')->redirect();
    }

    /**
     * Obtain the user information from 500px.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('500px')->user();
        } catch (Exception $e) {
            return Redirect::to('auth/500px');
        }

        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser, true);

        return Redirect::route('home');
    }

    /**
     * Return user if the user ID exists.
     * Create and return it if doesn't.
     *
     *
     * @param \Laravel\Socialite\One\User $pxUser
     * @return User
     */
    private function findOrCreateUser(\Laravel\Socialite\One\User $pxUser)
    {
        try {
            $user = User::findOrFail($pxUser->id);
            $user->fill([
                'name'            => $pxUser->getName(),
                'username'        => $pxUser->getNickname(),
                'email'           => $pxUser->getEmail(),
                'avatar'          => $pxUser->getAvatar(),
                'followers_count' => $pxUser->getRaw()['followers_count'],
            ])->save();
            return User::find($pxUser->getId());
        } catch (ModelNotFoundException $e) {
            $user = new User;
            $user->fill([
                'id'              => $pxUser->getId(),
                'name'            => $pxUser->getName(),
                'username'        => $pxUser->getNickname(),
                'email'           => $pxUser->getEmail(),
                'avatar'          => $pxUser->getAvatar(),
                'followers_count' => $pxUser->getRaw()['followers_count'],
                'access_token'    => $pxUser->token,
            ]);
            $user->setAttribute('access_token_secret', $pxUser->tokenSecret);
            $user->save();

            return User::find($pxUser->getId());
        }
    }
}
