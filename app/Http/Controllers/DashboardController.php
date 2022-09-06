<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect to each dashboard of role
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            $route = route('home');
        } else {
            if ($user->hasRole('super')) {
                $route = route('super.dashboard');
            } elseif ($user->hasRole('admin')) {
                $route = route('admin.dashboard');
            } elseif ($user->hasRole('user')) {
                $route = route('user.dashboard');
            }
        }

        return redirect($route);
    }
}
