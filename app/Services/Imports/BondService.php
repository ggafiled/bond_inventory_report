<?php

namespace App\Services\Imports;

use App\Models\StatusConfig;
use App\Models\ZoneConfig;
use Illuminate\Database\Eloquent\Collection;

class BondService
{

    public function __construct()
    {}

    public static function getTemplateInfo($file, $bond_status = null, $bond_area = null)
    {
        $status = StatusConfig::where('id', $bond_status)->first();
        $zone = ZoneConfig::whereIn('id', explode(',', $bond_area))->get();

        $statusFormat = "/" . $status->format . "/";
        $zoneFormat = "";
        foreach ($zone as $key => $item) {
            $zoneFormat = ($key == 0 ? "/" : "") . $zoneFormat . ($key > 0 ? "|" : "") . $item->format . ($key == count($zone) - 1 ? "/" : "");
        }

        //dd($statusFormat, $zoneFormat);

        return BondService::filterStatus($file, $statusFormat, $zoneFormat);
    }

    public static function filterStatus($file, $status_format, $zone_format)
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
                                "HAWB" => trim(substr($line, 0, 11)),
                                "Location" => trim(substr($line, 10, 11)),
                                "Arrival" => trim(substr($line, 21, 11)),
                                "Days" => trim(substr($line, 30, 6)),
                                "Consignee" => trim(substr($line, 35, 32)),
                                "Orig" => trim(substr($line, 66, 6)),
                                "Dest" => trim(substr($line, 72, 9)),
                                "Weight" => trim(substr($line, 79, 9)),
                                "Pcs" => trim(substr($line, 87, 7)),
                                "Scanned" => trim(substr($line, 92, 10)),
                                "Status" => trim(substr($line, 101, 9)),
                                "Bond Reason" => trim(substr($line, 109, 22)),
                            ],
                        );
                    }
                }
            }
            fclose($_file);
        }

        // Filter Status
        $spreadsheet = $spreadsheet->filter(function ($item) use ($status_format) {
            return preg_match($status_format, $item["Status"]);
        });

        // Filter selected zone
        $spreadsheet = $spreadsheet->filter(function ($item) use ($zone_format) {
            return preg_match($zone_format, $item["Location"]);
        });

        // Sort record by location A-Z
        $spreadsheet = $spreadsheet->sortBy('Location')->values()->toJson();
        $columns_forexport = [
            [
                'label' => 'HAWB',
                'field' => 'HAWB',
            ],
            [
                'label' => 'Location',
                'field' => 'Location',
            ],
            [
                'label' => 'Consignee Name',
                'field' => 'Consignee',
            ],
            [
                'label' => 'Weight',
                'field' => 'Weight',
            ],
            [
                'label' => 'Pcs',
                'field' => 'Pcs',
            ],
            [
                'label' => 'Scanned',
                'field' => 'Scanned',
            ],
            [
                'label' => 'Status',
                'field' => 'Status',
            ],
        ];
        $columns_forshow = [
            [
                'label' => 'HAWB',
                'field' => 'HAWB',
            ],
            [
                'label' => 'Location',
                'field' => 'Location',
            ],
            [
                'label' => 'Arrival',
                'field' => 'Arrival',
            ],
            [
                'label' => 'Days',
                'field' => 'Days',
            ],
            [
                'label' => 'Consignee Name',
                'field' => 'Consignee',
                'width' => '450px'
            ],
            [
                'label' => 'Orig',
                'field' => 'Orig',
            ],
            [
                'label' => 'Dest',
                'field' => 'Dest',
            ],
            [
                'label' => 'Weight',
                'field' => 'Weight',
            ],

            [
                'label' => 'Pcs',
                'field' => 'Pcs',
            ],
            [
                'label' => 'Scanned',
                'field' => 'Scanned',
            ],

            [
                'label' => 'Status',
                'field' => 'Status',
            ],
            [
                'label' => 'Bond Reason',
                'field' => 'Bond Reason',
                'width' => '150px'
            ],
        ];

        return ["results" => $spreadsheet, "columns_forexport" => json_encode($columns_forexport), "columns_forshow" => json_encode($columns_forshow)];
    }

}
