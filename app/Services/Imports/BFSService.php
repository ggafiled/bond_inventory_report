<?php

namespace App\Services\Imports;

use App\Models\StatusConfig;
use Illuminate\Database\Eloquent\Collection;

class BFSService
{

    public function __construct()
    {}

    public static function getTemplateInfo($file, $bond_status = null)
    {
        $status = StatusConfig::where('id', $bond_status)->first();

        $statusFormat = "/" . $status->format . "/";

        // dd(BFSService::filterStatus($file, $statusFormat));

        return BFSService::filterStatus($file, $statusFormat);
    }

    public static function filterStatus($file, $status_format)
    {
        /** This medthod for filter only BRKR status */
        $spreadsheet = new Collection();
        $flight_number = array();
        if ($_file = fopen($file, "r")) {
            while (!feof($_file)) {
                $line = fgets($_file);
                if (trim($line)) {
                    if(preg_match_all('/(TDOC:(.*))(Flight:(.*))(From:(.*))(Arrival:(.*))(Folio:(.*))/', trim($line))){
                        preg_match_all('/(TDOC:(?<TDOC>.*))(Flight:(?<Flight>.*))(From:(?<From>.*))(Arrival:(?<Arrival>.*))(Folio:(?<Folio>.*))/', trim($line), $flight_number);
                    }
                    // $line = str_replace("\r\n", '', $line);
                    if (preg_match_all('/[0-9]{10}/', substr(trim($line), 0, 11))) {
                        $spreadsheet->push(
                            [
                                "Flight" => trim($flight_number["Flight"][0]),
                                "HAWB" => trim(substr($line, 0, 11)),
                                "Pieces" => trim(substr($line, 10, 10)),
                                "Weight" => trim(substr($line, 20, 8)),
                                "Orig" => trim(substr($line, 28, 6)),
                                "Shipper" => trim(substr($line, 35, 32)),
                                "Dest" => trim(substr($line, 67, 6)),
                                "Value" => trim(substr($line, 73, 13)),
                                "Consignee" => trim(substr($line, 86, 30)),
                                "Status" => trim(substr($line, 118, 9)),
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

        // Convert collection to JSON format
        $spreadsheet = $spreadsheet->values()->toJson();
        $columns_forexport = [
            [
                'label' => 'Flight',
                'field' => 'Flight',
            ],
            [
                'label' => 'HAWB',
                'field' => 'HAWB',
            ],
            [
                'label' => 'Pieces',
                'field' => 'Pieces',
            ],
            [
                'label' => 'Weight',
                'field' => 'Weight',
            ],
            [
                'label' => 'Orig',
                'field' => 'Orig',
            ],
            [
                'label' => 'Status',
                'field' => 'Status',
            ],
        ];
        $columns_forshow = [
            [
                'label' => 'Flight',
                'field' => 'Flight',
            ],
            [
                'label' => 'HAWB',
                'field' => 'HAWB',
            ],
            [
                'label' => 'Pieces',
                'field' => 'Pieces',
            ],
            [
                'label' => 'Weight',
                'field' => 'Weight',
            ],
            [
                'label' => 'Orig',
                'field' => 'Orig',
            ],
            [
                'label' => 'Shipper',
                'field' => 'Shipper',
                'width' => '450px',
            ],
            [
                'label' => 'Dest',
                'field' => 'Dest',
            ],
            [
                'label' => 'Value',
                'field' => 'Value',
            ],
            [
                'label' => 'Consignee Name',
                'field' => 'Consignee',
                'width' => '450px',
            ],
            [
                'label' => 'Status',
                'field' => 'Status',
            ],
        ];

        return ["results" => $spreadsheet, "columns_forexport" => json_encode($columns_forexport), "columns_forshow" => json_encode($columns_forshow)];
    }

}
