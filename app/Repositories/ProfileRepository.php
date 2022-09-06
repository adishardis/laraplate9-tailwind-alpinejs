<?php

namespace App\Repositories;

use App\Events\UpdateAvatarNotification;
use App\Events\UpdateBackgroundNotification;
use App\Resources\ImageResource;
use Exception;
use Facades\App\Models\User;
use Facades\App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Log;

class ProfileRepository extends BaseRepository
{
    /**
     * Set default value user setting
     *
     * @param  array  $data
     * @return array
     */
    public function setDefaultValueUserSetting($data)
    {
        $keys = [
            'is_notif_alert' => 0,
        ];
        foreach ($keys as $key => $value) {
            $data[$key] = isset($data[$key]) ? $data[$key] : $value;
        }

        return $data;
    }

    /**
     * Update profile
     *
     * @param  array  $data
     * @return array
     */
    public function updateProfile($data)
    {
        \DB::beginTransaction();
        try {
            $user = auth()->user();

            $data['setting'] = $this->setDefaultValueUserSetting($data['setting'] ?? []);

            if (! empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            $user->update($data);

            $settingData = $data['setting'] ?? [];
            $setting = $user->setting;
            if (! $setting) {
                $user->setting()->create($settingData);
            } else {
                $setting->update($settingData);
            }

            \DB::commit();

            return $this->setResponse(true, __('Update profile successfully'));
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Update profile failed'), '', $th->getMessage());
        }
    }

    /**
     * Get user avatar
     *
     * @param  \App\Models\User::id  $userId
     * @return string
     */
    public function getAvatar($userId = null)
    {
        try {
            $user = $userId ? User::findOrFail($userId) : auth()->user();
            $avatar = $user->avatar;
            if (! $avatar) {
                throw new Exception('Avatar not found', 404);
            }
            $data = new ImageResource($avatar);

            return $this->setResponse(true, __('Get avatar successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Get avatar failed'), '', $th->getMessage());
        }
    }

    /**
     * Get user avatar thumbnail
     *
     * @return string
     */
    public function getAvatarThumb()
    {
        try {
            $avatar = auth()->user()->avatar;
            if (! $avatar) {
                // throw new Exception("Avatar not found", 404);
                return $this->setResponse(false, __('Avatar not found'));
            }
            $image = ImageRepository::getThumbnail($avatar->id);
            $data = new ImageResource($image);

            return $this->setResponse(true, __('Get avatar thumb successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Get avatar thumb failed'), '', $th->getMessage());
        }
    }

    /**
     * Change user avatar
     *
     * @param  binary  $file
     * @return App\Models\Image
     */
    public function updateAvatar($file)
    {
        \DB::beginTransaction();
        try {
            $user = auth()->user();
            $image = ImageRepository::upload($file, ['thumb', 'medium', 'large'], 500, '', 'avatar');
            if ($user->avatar) {
                $user->avatar()->delete();
            }
            UpdateAvatarNotification::dispatch($user->id);
            $data = $user->avatar()->save($image);

            \DB::commit();

            return $this->setResponse(true, __('Update avatar successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Update avatar failed'), '', $th->getMessage());
        }
    }

    /**
     * Get user background
     *
     * @param  \App\Models\User::id  $userId
     * @return string
     */
    public function getBackground($userId = null)
    {
        try {
            $user = $userId ? User::findOrFail($userId) : auth()->user();
            $background = $user->background;
            if (! $background) {
                // throw new Exception("Background not found", 404);
                return $this->setResponse(false, __('Background not found'));
            }
            $data = new ImageResource($background);

            return $this->setResponse(true, __('Get background successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Get background failed'), '', $th->getMessage());
        }
    }

    /**
     * Change user background
     *
     * @param  binary  $file
     * @return App\Models\Image
     */
    public function updateBackground($file)
    {
        \DB::beginTransaction();
        try {
            $user = auth()->user();
            $image = ImageRepository::upload($file, ['thumb', 'medium', 'large'], 500, '', 'background');
            if ($user->background) {
                $user->background()->delete();
            }
            UpdateBackgroundNotification::dispatch($user->id);
            $data = $user->background()->save($image);

            \DB::commit();

            return $this->setResponse(true, __('Update background successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Update background failed'), '', $th->getMessage());
        }
    }
}
