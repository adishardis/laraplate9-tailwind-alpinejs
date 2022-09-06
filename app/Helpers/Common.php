<?php

if (! function_exists('checkPerm')) {
    /**
     * Check Permision Role using Entrust
     *
     * @param  string  $name
     */
    function checkPerm($name = '', $show_abort = false)
    {
        if ($show_abort) {
            if (! auth()->user()->can($name)) {
                abort(401);
            }
        } else {
            return auth()->user()->can($name) ? true : false;
        }
    }
}

if (! function_exists('setAlert')) {
    /**
     * Set Notif Alert
     *
     * @param  string  $type
     * @param  string  $message
     */
    function setAlert($type = 'warning', $message = '')
    {
        $data = [
            'type' => $type,
            'message' => $message,
        ];

        return session()->flash('notif_alert', $data);
    }
}

if (! function_exists('checkMethod')) {
    /**
     * Check Method for Action
     *
     * @param  string  $action
     * @param  \Illuminate\Http\Request | array  $request
     * @param  bool  $showAbort
     * @param  array  $headers
     */
    function checkMethod($action, $request, $showAbort = false, $headers = [])
    {
        $availableMethods = [
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
        ];
        if (! isset($availableMethods[$action]) ||
            ! in_array($request->method(), $availableMethods[$action])) {
            if ($showAbort) {
                abort(405, __('Method not allowed'), $headers);
            }

            return false;
        }

        return true;
    }
}

if (! function_exists('checkApiMethod')) {
    /**
     * Check Method for Action
     *
     * @param  string  $action
     * @param  \Illuminate\Http\Request | array  $request
     * @param  bool  $showAbort
     */
    function checkApiMethod($action, $request, $showAbort = false)
    {
        return checkMethod($action, $request, $showAbort, ['Content-Type' => 'application/json']);
    }
}
