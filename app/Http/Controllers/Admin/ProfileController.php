<?php

namespace App\Http\Controllers\Admin;

use App\Cores\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use Facades\App\Repositories\ProfileRepository;
use Facades\App\Repositories\SummaryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.form', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request)
    {
        $data = $request->validated();
        $data = ProfileRepository::updateProfile($data);
        setAlert($data['status'] ? 'success' : 'warning', $data['message']);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            $data = SummaryRepository::getSummaryUserProfile();
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
