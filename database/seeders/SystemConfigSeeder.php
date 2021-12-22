<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_configs')->insert([
            [
              'id' => 1,
              'title' => 'RLSE',
              'tooltip' => 'For filter release shipment only',
              'format' => '[a-zA-Z]{1}\\/RLS[E0-9]?',
              'created_at' => '2021-12-22T08:32:43.000000Z',
              'updated_at' => '2021-12-22T12:35:15.000000Z',
              'deleted_at' => NULL,
            ],
            [
              'id' => 2,
              'title' => 'BRKR/BTO',
              'tooltip' => 'For filter broker clearance shipment only',
              'format' => '[a-zA-Z]{1}\\/(BRKR|BTO)',
              'created_at' => '2021-12-22T08:34:42.000000Z',
              'updated_at' => '2021-12-22T12:35:29.000000Z',
              'deleted_at' => NULL,
            ],
          ]);

        DB::table('zone_configs')->insert([
            [
              'id' => 1,
              'title' => 'COY Old',
              'subtitle' => 'A-A-1, E-AA-1',
              'tooltip' => 'COY Zone on the left side.<br/>It has been stored at shield id start with A-A-1 to Z-Z-1. <br/> And E-AA-1 location',
              'format' => '((?![nN])[a-zA-z]{1}-[a-zA-z]{1}-[0-9]{1,5})|(E[a-zA-Z]?-[a-zA-z]{2}-[0-9]{1,5})',
              'created_at' => '2021-12-22T08:48:04.000000Z',
              'updated_at' => '2021-12-22T08:48:04.000000Z',
              'deleted_at' => NULL,
            ],
            [
              'id' => 2,
              'title' => 'COY New',
              'subtitle' => 'A-AA-1, CLD',
              'tooltip' => 'COY Zone on the front side.<br/>It has been stored at shield id start with A-AA-1 to Z-ZZ-1 <br/> And Freeze Zone starting with CLD',
              'format' => '((?![nfeNFE|(ex|EX)])[a-zA-Z]{1}-[a-zA-z]{2}-[0-9]{1,5})|(CLD.*)',
              'created_at' => '2021-12-22T08:48:40.000000Z',
              'updated_at' => '2021-12-22T08:48:40.000000Z',
              'deleted_at' => NULL,
            ],
            [
              'id' => 3,
              'title' => 'Flyer',
              'subtitle' => 'F-AA-1',
              'tooltip' => 'Area of Flyer Zone has shield id<br/>start with F-A-1 to F-Z-1',
              'format' => 'F-(?![nN])[a-zA-z]{2}-[0-9]{1,5}',
              'created_at' => '2021-12-22T08:49:06.000000Z',
              'updated_at' => '2021-12-22T08:49:06.000000Z',
              'deleted_at' => NULL,
            ],
            [
              'id' => 4,
              'title' => 'NCY',
              'subtitle' => 'N-AA-1, F-NA-1, OT-CIB',
              'tooltip' => 'Area of large shipment has been <br/> stored on the first floor.',
              'format' => '(N-[a-zA-z]{1,2}-[0-9]{1,5})|(F-N[a-zA-Z]{1}-[0-9]{1,5})|(OT.*)',
              'created_at' => '2021-12-22T08:49:33.000000Z',
              'updated_at' => '2021-12-22T08:49:33.000000Z',
              'deleted_at' => NULL,
            ],
          ]);
    }
}