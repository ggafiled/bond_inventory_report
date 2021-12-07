<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController;
use App\Services\Imports\ImportCustomerTemplateService;
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
        try {
            return $this->sendResponse(["fileInfo" => ImportCustomerTemplateService::getTemplateInfo($request->file("file"), $request->get("bond_status"), $request->get("bond_area"))], trans('actions.get.success'));
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage());
        }
    }
}