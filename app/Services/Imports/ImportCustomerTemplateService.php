<?php

namespace App\Services\Imports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use PhpParser\ErrorHandler\Collecting;

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

    public static function forRLSE($file, $bond_area)
    {
        /** This medthod for filter only RLSE status */

        $spreadsheet = new Collection();
        if ($_file = fopen($file, "r")) {
            while (!feof($_file)) {
                $line = fgets($_file);
                if (trim($line)) {
                    if(preg_match_all('/[0-9]{10}/', substr($line, 0, 10))){
                        $record = array(
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
                        );
    
                        $spreadsheet->push($record);
                    }
                }
            }
            fclose($_file);
        }

        // Filter Status and Only selected location
        $spreadsheet = $spreadsheet->filter(function ($item){
            return preg_match("/[a-zA-Z]{1}\/RLS4/", $item["Status"]);
        });

        $spreadsheet = $spreadsheet->sortBy('Location')->values()->all(); // Sort record by location A-Z

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
                    if(preg_match_all('/[0-9]{10}/', substr(trim($line), 0, 10))){
                        $record = array(
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
                        );
    
                        $spreadsheet->push($record);
                    }
                }
            }
            fclose($_file);
        }

        // Filter Status and Only selected location
        $spreadsheet = $spreadsheet->filter(function ($item){
            return preg_match("/[a-zA-Z]{1}\/BRKR/", $item["Status"]);
        });

        $spreadsheet = $spreadsheet->sortBy('Location')->values()->all(); // Sort record by location A-Z

        return ["results" => $spreadsheet, "bondArea" => $bond_area];
    }

}
