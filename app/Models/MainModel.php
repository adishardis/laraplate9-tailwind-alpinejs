<?php

namespace App\Models;

trait MainModel
{
    /**
     * Created with Log
     *
     * @param  array  $data
     * @param  bool  $log
     * @return object
     */
    public function createNew($data = [], $log = false)
    {
        $model = $this->create($data);
        if ($log) {
            $user = auth()->user();
            if ($user) {
                $model->logs()->create([
                    'user_id' => $user->id,
                    'log_type' => 'create',
                    'log_data' => json_encode($data),
                ]);
            }
        }

        return $model;
    }

    /**
     * Update Row by ID with Log
     *
     * @param  int  $id
     * @param  array  $data
     * @return object
     */
    public function updateById($id, $data = [], $log = false)
    {
        $model = $this->findOrFail($id);
        $model->update($data);
        $changes = $model->getChanges();

        /* Insert the changes into ActivityLog */
        if ($log && ! empty($changes)) {
            $user = auth()->user();
            if ($user) {
                $model->logs()->create([
                    'user_id' => $user->id,
                    'log_type' => 'update',
                    'log_data' => json_encode($changes),
                ]);
            }
        }

        return $model;
    }

    /**
     * Delete Row by ID with Log
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteById($id, $log = false)
    {
        $model = $this->findOrFail($id);
        /* Insert the changes into ActivityLog */
        if ($log) {
            $user = auth()->user();
            if ($user) {
                $model->logs()->create([
                    'user_id' => $user->id,
                    'log_type' => 'delete',
                    'log_data' => json_encode($model),
                ]);
            }
        }

        return $model->delete();
    }

    /**
     * First or Create with Log
     *
     * @param  array  $data
     * @param  bool  $log
     * @return object
     */
    public function firstOrCreateWithLog($data = [], $log = false)
    {
        $model = $this->firstOrCreate($data);
        if ($log) {
            $user = auth()->user();
            if ($user) {
                $model->logs()->create([
                    'user_id' => $user->id,
                    'log_type' => 'create',
                    'log_data' => json_encode($data),
                ]);
            }
        }

        return $model;
    }

    /**
     * Get all of the table's logs
     */
    public function logs()
    {
        return $this->morphMany(ActivityLog::class, 'logable');
    }
}
