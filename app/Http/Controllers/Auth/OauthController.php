<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Repositories\OauthRepository;
use Illuminate\Support\Facades\Log;

class OauthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redirect socialite
     *
     * @param string $socialite
     * @return \Illuminate\Http\Response
     */
    public function redirectSocialite($socialite)
    {
        try {
            return OauthRepository::redirect($socialite);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return redirect()->route('login')->with('error', "Login with ".(strtoupper($socialite))." failed");
        }
    }

    /**
     * Handle callback socialite
     *
     * @param string $socialite
     * @return \Illuminate\Http\Response
     */
    public function handleCallback($socialite)
    {
        try {
            OauthRepository::callback($socialite);
            return redirect()->route('dashboard');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return redirect()->route('login')->with('error', "Login with ".(strtoupper($socialite))." failed");
        }
    }
}
