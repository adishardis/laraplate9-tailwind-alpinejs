<?php

namespace App\Http\Controllers\Admin;

use App\Cores\ApiResponse;
use App\Http\Controllers\Controller;
use Facades\App\Repositories\SummaryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * Display Dashboard Page
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.dashboard');
    }

    /**
     * Get summary data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    protected function getSummary($request)
    {
        try {
            $data = SummaryRepository::getSummaryPost();
            return $this->responseJson(
                'success',
                'Get summary successfully',
                $data,
                200
            );
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->responseJson(
                'error',
                'Get summary failed',
                $th,
                400
            );
        }
    }

    /**
     * Fetch Request
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return match ($request->mode) {
            'summary' => (
                $this->getSummary($request)
            ),
        };
    }
}
