<?php

namespace App\Services\Imports;

use Illuminate\Support\Str;

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

        $spreadsheet = array();
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
    
                        array_push($spreadsheet, $record);
                    }
                }
            }
            fclose($_file);
        }

        return ["results" => $spreadsheet, "bondArea" => $bond_area];
    }

    public static function forBRKR($file, $bond_area)
    {
        /** This medthod for filter only BRKR status */
   
        $spreadsheet = array();
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
    
                        array_push($spreadsheet, $record);
                    }
                }
            }
            fclose($_file);
        }

        return ["results" => $spreadsheet, "bondArea" => $bond_area];
    }

}
