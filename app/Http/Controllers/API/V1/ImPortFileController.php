<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController;
use App\Services\Imports\BondService;
use App\Services\Imports\BFSService;
use App\Models\StatusConfig;
use Exception;
use Illuminate\Http\Request;

class ImPortFileController extends BaseController
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function getInfo(Request $request)
    {
        // dd($request->all());
        $workingInType = StatusConfig::where('id',$request->input('bond_status'))->first();
        try {
            switch(strtolower($workingInType->type)){
                case "bond":
                    return $this->sendResponse(["fileInfo" => BondService::getTemplateInfo($request->file("file"), $request->get("bond_status"), $request->get("bond_area"))], trans('actions.get.success'));
                    break;
                case "bfs":
                    return $this->sendResponse(["fileInfo" => BFSService::getTemplateInfo($request->file("file"), $request->get("bond_status"))], trans('actions.get.success'));
                    break;
                default:
                    return $this->sendResponse(["fileInfo" => BondService::getTemplateInfo($request->file("file"), $request->get("bond_status"), $request->get("bond_area"))], trans('actions.get.success'));
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), $ex->getMessage());
        }
    }
}