<?php

namespace App\Services\Imports;

use Illuminate\Database\Eloquent\Collection;

class ImportCustomerTemplateService
{

    public function __construct()
    {}

    public static function getTemplateInfo($file, $bond_status = null, $bond_area = null)
    {
        switch (strtoupper($bond_status)) {
            case "RLSE":
                return ImportCustomerTemplateService::forRLSE($file, $bond_area);
                break;
            case "BRKR":
                return ImportCustomerTemplateService::forBRKR($file, $bond_area);
            default:
                break;
        }
    }

    public static function filterByLocation($spreadsheet = null, $bond_area = null)
    {
        $areaList = array(
            "COY Old" => "([a-zA-z]{1}-[a-zA-z]{1}-[0-9]{1})|(E-[a-zA-z]{2}-[0-9]{1})", // All format A-A-1 and E-AA-1 edit on 21/12/2564
            "COY New" => "(?![nfeNFE])[a-zA-Z]{1}-[a-zA-z]{2}-[0-9]{1}", // All format A-AA-1 except N-AA-1 and E-AA-1 edit on 21/12/2564
            "Flyer" => "F-(?![nN])[a-zA-z]{2}-[0-9]{1}", // All F-AA-1 except F-NA-1 edit on 21/12/2564
            "NCY" => "(N-[a-zA-z]{2}-[0-9]{1})|(F-N[a-zA-Z]{1}-[0-9]{1})", // Only N-AA-1 and F-NA-1 edit on 21/12/2564
        );

        $regex = "";
        $request_area = explode(",", $bond_area);
        foreach ($request_area as $key => $item) {
            $regex = ($key == 0 ? "/" : "") . $regex . ($key > 0 ? "|" : "") . $areaList[$item] . ($key == count($request_area) - 1 ? "/" : "");
        }
        $spreadsheet = $spreadsheet->filter(function ($item) use ($regex) {
            return preg_match($regex, $item["Location"]);
        });

        return $spreadsheet;
    }

    public static function forRLSE($file, $bond_area)
    {
        /** This medthod for filter only RLSE status */

        $spreadsheet = new Collection();
        if ($_file = fopen($file, "r")) {
            while (!feof($_file)) {
                $line = fgets($_file);
                if (trim($line)) {
                    if (preg_match_all('/[0-9]{10}/', substr($line, 0, 10))) {
                        $spreadsheet->push(
                            [
                                "HAWB" => substr($line, 0, 10),
                                "Location" => substr($line, 10, 10),
                                "Arrival" => substr($line, 20, 10),
                                "Days" => substr($line, 30, 5),
                                "Consignee" => substr($line, 35, 31),
                                "Orig" => substr($line, 66, 5),
                                "Dest" => substr($line, 72, 8),
                                "Weight" => substr($line, 79, 8),
                                "Pcs" => substr($line, 87, 6),
                                "Scanned" => substr($line, 92, 9),
                                "Status" => substr($line, 101, 8),
                                "Bond Reason" => substr($line, 109, 21),
                            ],
                        );
                    }
                }
            }
            fclose($_file);
        }

        // dd($spreadsheet->toJson());

        // Filter Status and Only selected location
        $spreadsheet = $spreadsheet->filter(function ($item) {
            return preg_match("/[a-zA-Z]{1}\/RLS[E0-9]?/", $item["Status"]); // 21/12/2564
        });

        $spreadsheet = ImportCustomerTemplateService::filterByLocation($spreadsheet, $bond_area);

        $spreadsheet = $spreadsheet->sortBy('Location')->values()->toJson(); // Sort record by location A-Z

        return ["results" => $spreadsheet, "bondArea" => $bond_area];
    }

    public static function forBRKR($file, $bond_area)
    {
        /** This medthod for filter only BRKR status */

        $spreadsheet = new Collection();
        if ($_file = fopen($file, "r")) {
            while (!feof($_file)) {
                $line = fgets($_file);
                if (trim($line)) {
                    // $line = str_replace("\r\n", '', $line);
                    if (preg_match_all('/[0-9]{10}/', substr(trim($line), 0, 10))) {
                        $spreadsheet->push(
                            [
                                "HAWB" => substr($line, 0, 10),
                                "Location" => substr($line, 10, 10),
                                "Arrival" => substr($line, 20, 10),
                                "Days" => substr($line, 30, 5),
                                "Consignee" => substr($line, 35, 31),
                                "Orig" => substr($line, 66, 5),
                                "Dest" => substr($line, 72, 8),
                                "Weight" => substr($line, 79, 8),
                                "Pcs" => substr($line, 87, 6),
                                "Scanned" => substr($line, 92, 9),
                                "Status" => substr($line, 101, 8),
                                "Bond Reason" => substr($line, 109, 21),
                            ],
                        );
                    }
                }
            }
            fclose($_file);
        }

        // Filter Status and Only selected location
        $spreadsheet = $spreadsheet->filter(function ($item) {
            return preg_match("/[a-zA-Z]{1}\/BRKR/", $item["Status"]);
        });

        $spreadsheet = ImportCustomerTemplateService::filterByLocation($spreadsheet, $bond_area);

        $spreadsheet = $spreadsheet->sortBy('Location')->values()->toJson(); // Sort record by location A-Z

        return ["results" => $spreadsheet, "bondArea" => $bond_area];
    }

}
