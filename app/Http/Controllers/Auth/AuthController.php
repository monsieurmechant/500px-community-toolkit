<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

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
        if (null !== $authUser = User::where('user_id', $pxUser->id)->first()) {
            return $authUser;
        }
        $user = new User;
        $user->fill([
            'name'                => $pxUser->getName(),
            'nickname'            => $pxUser->getNickname(),
            'email'               => $pxUser->getEmail(),
            'user_id'             => $pxUser->getId(),
            'avatar'              => $pxUser->getAvatar(),
            'access_token'        => $pxUser->token,
        ]);
        $user->setAttribute('access_token_secret', $pxUser->tokenSecret);
        $user->save();
    }
}
