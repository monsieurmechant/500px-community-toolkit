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
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::with('500px')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
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
     * Return user if exists; create and return if doesn't
     *
     * @param $pxUser
     * @return User
     */
    private function findOrCreateUser(\Laravel\Socialite\One\User $pxUser)
    {
        if (null !== $authUser = User::where('user_id', $pxUser->id)->first()) {
            return $authUser;
        }

        return User::create([
            'name'                => $pxUser->getName(),
            'nickname'            => $pxUser->getNickname(),
            'email'               => $pxUser->getEmail(),
            'user_id'             => $pxUser->getId(),
            'avatar'              => $pxUser->getAvatar(),
            'access_token'        => $pxUser->token,
            'access_token_secret' => $pxUser->tokenSecret,
        ]);
    }
}
