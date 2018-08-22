<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CitiesModel;
use GuzzleHttp\Client;
use File;
use Zipper;
use ZipArchive;

class UpdateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update data in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->columns = ['geonameid', 'name', 'asciiname','alternatenames', 'latitude', 'longitude', 'featureclass', 'featurecode', 'countrycode', 'cc2', 'admin1code', 'admin2code', 'admin3code', 'admin4code', 'population', 'elevation', 'dem', 'timezone', 'modificationdate'];
        $this->pathToZip = storage_path('zipfile');
        $this->file = 'RU.txt';
        $this->guzzle = new Client();
        $this->objCityModel = new CitiesModel();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getZipFile()
    {
        $zip = new ZipArchive;
        $res = $zip->open($this->pathToZip . '/RU.zip');
        if ($res === TRUE) {
            $oldModificationDate = $zip->statIndex(1)['mtime'];
        }

        $url = "http://download.geonames.org/export/dump/RU.zip";
        $guzzle = new Client();
        $response = $guzzle->get($url);
        File::put($this->pathToZip . '/RU.zip', $response->getBody());
        Zipper::make($this->pathToZip .'/RU.zip')->extractTo($this->pathToZip);
        $res = $zip->open($this->pathToZip . '/RU.zip');
        $newModificationDate = $zip->statIndex(1)['mtime'];

        if(isset($oldModificationDate) && $oldModificationDate == $newModificationDate)
        {
            $updateDatabase = 0;   //modification date is the same, no need to uodate database
        }
        else
        {
            $updateDatabase = 1;  // database update needed
        }
         return $updateDatabase;

    }

    public function handle()
    {
        $dbUpdate = $this->getZipFile();

        if($dbUpdate == 1)
        {
            $file = $this->pathToZip . '\\' . $this->file;
            $fopen = fopen($file, 'r');
            $fread = fread($fopen,filesize($file));
            fclose($fopen);
            $remove = "\n";
            $split = explode($remove, $fread);

            $array[] = null;
            $tab = "\t";

            foreach ($split as $string)
            {
                if($string)
                {
                    $row = explode($tab, $string);
                    $x = array_combine($this->columns, $row);
                    if(empty($x['elevation']))
                    {
                        $x['elevation'] = 0;
                    }
                    array_push($array,$x);
                }
            }

            $this->objCityModel->insertDB(array_slice($array, 1));
        }
        echo 'no update needed';

    }
}
