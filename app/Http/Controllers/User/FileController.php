<?php

namespace App\Http\Controllers\User;

use App\Cores\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Repositories\ProfileRepository;

class FileController extends Controller
{
    use ApiResponse;

    /**
     * Get Avatar
     *
     * @param \App\Models\User::id $userId
     * return Json
     */
    protected function getAvatar($userId)
    {
        $data = ProfileRepository::getAvatar($userId);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'] ?? '',
            $data['data'] ?? '',
            $data['status'] ? 200 : 500
        );
    }

    /**
     * Update Avatar
     *
     * @param binary $file
     * return Json
     */
    protected function updateAvatar($file)
    {
        $data = ProfileRepository::updateAvatar($file);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'] ?? '',
            $data['data'] ?? '',
            $data['status'] ? 200 : 500
        );
    }

    /**
     * Get Background
     *
     * @param \App\Models\User::id $userId
     * return Json
     */
    protected function getBackground($userId)
    {
        $data = ProfileRepository::getBackground($userId);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'] ?? '',
            $data['data'] ?? '',
            $data['status'] ? 200 : 500
        );
    }

    /**
     * Update Background
     *
     * @param binary $file
     * return Json
     */
    protected function updateBackground($file)
    {
        $data = ProfileRepository::updateBackground($file);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'] ?? '',
            $data['data'] ?? '',
            $data['status'] ? 200 : 500
        );
    }

    /**
     * Handle all fetch
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        switch ($request->mode) {
            case 'get-avatar':
                return $this->getAvatar(auth()->id());
                break;
            case 'upload-avatar':
                return $this->updateAvatar($request->file);
                break;
            case 'get-background':
                return $this->getBackground(auth()->id());
                break;
            case 'upload-background':
                return $this->updateBackground($request->file);
                break;
        }
    }
}
