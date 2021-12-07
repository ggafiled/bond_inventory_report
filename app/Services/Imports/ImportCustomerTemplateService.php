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
                            "Location" => substr($line, 10, 20),
                            "Arrival" => substr($line, 20, 30),
                            "Days" => substr($line, 30, 35),
                            "Consignee" => substr($line, 35, 66),
                            "Orig" => substr($line, 66, 71),
                            "Dest" => substr($line, 72, 79),
                            "Weight" => substr($line, 79, 87),
                            "Pcs" => substr($line, 87, 92),
                            "Scanned" => substr($line, 92, 101),
                            "Status" => substr($line, 101, 109),
                            "Bond Reason" => substr($line, 109, 130),
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
                    if(preg_match_all('/[0-9]{10}/', substr($line, 0, 10))){
                        $record = array(
                            "HAWB" => substr($line, 0, 10),
                            "Location" => substr($line, 10, 20),
                            "Arrival" => substr($line, 20, 30),
                            "Days" => substr($line, 30, 35),
                            "Consignee" => substr($line, 35, 66),
                            "Orig" => substr($line, 66, 71),
                            "Dest" => substr($line, 72, 79),
                            "Weight" => substr($line, 79, 87),
                            "Pcs" => substr($line, 87, 92),
                            "Scanned" => substr($line, 92, 101),
                            "Status" => substr($line, 101, 109),
                            "Bond Reason" => substr($line, 109, 130),
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
