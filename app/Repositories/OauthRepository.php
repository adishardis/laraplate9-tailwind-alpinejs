<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserSocialAccount;
use Facades\App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OauthRepository
{
    /**
     * Redirect socialite
     *
     * @param  string  $socialite
     * @return \Illuminate\Http\Response
     */
    public function redirect($socialite)
    {
        try {
            return Socialite::driver($socialite)->redirect();
        } catch (\Throwable $th) {
            Log::error($th);
            throw $th;
        }
    }

    /**
     * Callback socialite
     *
     * @param  string  $socialite
     * @return void
     */
    public function callback($socialite)
    {
        $socialiteUser = Socialite::driver($socialite)->user();
        if (! in_array($socialite, ['github', 'google', 'facebook'])) {
            throw 'Socialite not available';
        }
        $user = $this->handleCreateSocialiteUser($socialite, $socialiteUser);
        // Login User
        Auth::login($user);
    }

    /**
     * Handle create or update user socialite
     *
     * @param  string  $socialite
     * @param  object  $socialiteUser
     * @return App\Models\User
     */
    private function handleCreateSocialiteUser($socialite, $socialiteUser)
    {
        return DB::transaction(function () use ($socialite, $socialiteUser) {
            $userSocialData = [
                'socialite' => $socialite,
                'uid' => $socialiteUser->id,
            ];
            $userSocial = UserSocialAccount::where($userSocialData)->first();
            if (! $userSocial) {
                $user = User::firstWhere('email', $socialiteUser->email);
                if (! $user) {
                    $userData = [
                        'name' => $socialiteUser->name,
                        'email' => $socialiteUser->email,
                        'password' => $socialiteUser->token,
                        'email_verified_at' => now(),
                    ];
                    $data = UserRepository::registerUser($userData);
                    $user = $data['data'];
                }
                $userSocialData['user_id'] = $user->id;
                UserSocialAccount::create($userSocialData);
            } else {
                $user = $userSocial->user;
            }

            return $user;
        });
    }
}
