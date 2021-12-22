<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController;
use App\Models\StatusConfig;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BondStatusConfigController extends BaseController
{

    public function __construct()
    {
        // $this->middleware('auth:api');
        // $this->middleware('role:superadministrator');
    }

    /**
     * Retreive data
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $status = StatusConfig::withTrashed()->get();
            return $this->sendResponse($status, trans('actions.get.success'));
        } catch (Exception $ex) {
            return $this->sendError([], trans('actions.get.failed'));
        }
    }

    /**
     * Retreive data
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatusInfo()
    {
        try {
            $status = StatusConfig::get();
            return $this->sendResponse($status, trans('actions.get.success'));
        } catch (Exception $ex) {
            return $this->sendError([], trans('actions.get.failed'));
        }
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
        try {
            $status = StatusConfig::create([
                'title' => trim(strtoupper($request['title'])),
                'tooltip' => trim($request['tooltip']),
                'format' => trim($request['format']),
            ]);
            return $this->sendResponse($status, trans('actions.store.success'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), trans('actions.store.failed'));
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $zone = StatusConfig::withTrashed()->findOrFail($id);

            if((int) $request->input("item_status") === 1){
                $zone->deleted_at = null;
            }else{
                $zone->deleted_at = Carbon::now();
            }

             $zone->update([
                'title' => trim(strtoupper($request['title'])),
                'tooltip' => trim($request['tooltip']),
                'format' => trim($request['format']),
            ]);

            return $this->sendResponse($zone, trans('actions.updated.success'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), trans('actions.updated.failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $zone = StatusConfig::withTrashed()->findOrFail($id);
            // delete the user

            $zone->delete();
            return $this->sendResponse($zone, trans('actions.destroy.success'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), trans('actions.destroy.failed'));
        }
    }
}
