<?php

namespace App\Repositories;

use App\Events\ReadNotification;
use App\Events\ReadAllNotification;
use App\Events\UnreadNotification;
use App\Events\UnreadAllNotification;
use App\Models\Notification;
use App\Resources\NotificationResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class NotificationRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Notifications
     *
     * @return Json|Array
     */
    public function datatable(Request $request)
    {
        abort_if(!auth()->user(), 401);
        try {
            $query = Notification::byUserId();
            $filters = [
                [
                    'field' => 'subject',
                    'value' => $request->subject,
                    'query' => 'like',
                ],
                [
                    'field' => 'message',
                    'value' => $request->message,
                    'query' => 'like',
                ],
                [
                    'field' => 'type',
                    'value' => $request->type,
                ],
            ];
            $request->sortBy =  $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);
            return NotificationResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Failed get notifications'));
        }
    }

    /**
     * Get total unread notifications
     *
     * @return array
     */
    public function totalUnread()
    {
        abort_if(!auth()->user(), 401);
        $total = Notification::byUserId()->unread()->count();
        return [
            'total' => $total
        ];
    }

    /**
     * Read Notifification / Read All
     *
     * @param int $id
     * @param string $type
     * @return array
     */
    public function read($id = null, $type = 'all')
    {
        abort_if(!auth()->user(), 401);
        try {
            $user = auth()->user();
            $message = '';
            if ($id) {
                $notif = Notification::findOrFail($id);
                if ($user->id == $notif->user_id) {
                    $notif->update(['read_at' => time()]);
                    $message = __('Read notification successfully');
                    ReadNotification::dispatch($user->id);
                }
            } elseif ($type == 'all') {
                Notification::byUserId()
                            ->whereNull('read_at')
                            ->update(['read_at' => time()]);
                $message = __('Read all notification successfully');
                ReadAllNotification::dispatch($user->id);
            }
            return $this->setResponse(true, $message);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Read notifiaction failed'), '', $th->getMessage());
        }
    }

    /**
     * Unread Notifification / Unread All
     *
     * @param int $id
     * @param string $type
     * @return array
     */
    public function unread($id = null, $type = 'all')
    {
        abort_if(!auth()->user(), 401);
        try {
            $user = auth()->user();
            $message = '';
            if ($id) {
                $notif = Notification::findOrFail($id);
                if ($user->id == $notif->user_id) {
                    $notif->update(['read_at' => null]);
                    $message = __('Unread notification successfully');
                    UnreadNotification::dispatch($user->id);
                }
            } elseif ($type == 'all') {
                Notification::byUserId()
                            ->whereNotNull('read_at')
                            ->update(['read_at' => null]);
                $message = __('Unread all notification successfully');
                UnreadAllNotification::dispatch($user->id);
            }
            return $this->setResponse(true, $message);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Unread notifiaction failed'), '', $th->getMessage());
        }
    }
}
