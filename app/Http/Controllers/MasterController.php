<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Repositories\RoleRepository;
use Facades\App\Repositories\NotificationRepository;
use Facades\App\Repositories\ProfileRepository;

class MasterController extends Controller
{
    /**
     * Fetch Request
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return match ($request->mode) {
            'get-avatar' => (
                ProfileRepository::getAvatar()
            ),
            'get-avatar-thumb' => (
                ProfileRepository::getAvatarThumb()
            ),
            'get-background' => (
                ProfileRepository::getBackground()
            ),
            'roles' => (
                RoleRepository::datatable($request)
            ),
            'notifications' => (
                NotificationRepository::datatable($request)
            ),
            'get-total-unread-notifications' => (
                NotificationRepository::totalUnread()
            ),
            'read-notification' => (
                NotificationRepository::read($request->id)
            ),
            'unread-notification' => (
                NotificationRepository::unread($request->id)
            ),
            'read-all-notification' => (
                NotificationRepository::read()
            ),
            'unread-all-notification' => (
                NotificationRepository::unread()
            ),
        };
    }
}
