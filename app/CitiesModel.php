<?php
/**
 * Created by PhpStorm.
 * User: Lusine
 * Date: 8/15/2018
 * Time: 4:09 PM
 */

namespace App;

use DB;

class CitiesModel
{
    protected $table = 'Cities';

    // protected $primaryKey = 'geonameid';

    public function insertDB($aryData)
    {
        $arySelect = DB::table($this->table)
            ->select()->get();

        if ($arySelect->count() == 0) {
            $newChunkArray = array_chunk($aryData, 200);
            foreach ($newChunkArray as $step) {
                $query = DB::table($this->table)
                    ->insert($aryData);
            }

        } else {
            foreach ($aryData as $eachCity) {
                $city = DB::table($this->table)
                    ->select()
                    ->where('geonameid', $eachCity['geonameid'])
                    ->first();

                if ($city) {
                    DB::table($this->table)->Where('geonameid', $eachCity['geonameid'])
                        ->update($eachCity);
                } else {
                    DB::table($this->table)
                        ->insert($eachCity);
                }
            }
        }
    }
}