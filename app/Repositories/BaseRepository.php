<?php

namespace App\Repositories;

class BaseRepository
{
    /**
     * Set response
     *
     * @param  bool  $status
     * @param  string  $message
     * @param  array|object  $data
     * @param  string  $note
     * @return array
     */
    protected function setResponse($status = false, $message = 'Failed', $data = [], $note = '')
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'note' => $note,
        ];
    }
}
