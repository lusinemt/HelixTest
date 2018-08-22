<?php
/**
 * Created by PhpStorm.
 * User: Lusine
 * Date: 8/15/2018
 * Time: 4:24 PM
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use DB;
use App\CitiesModel;


class SearchFormController extends Controller
{
    private $columns = array();
    private $pathToZip;

    public function __construct()
    {

    }

    public function index()
    {
        return view('form');
    }

    public function autocompleteSearch()
    {
        $term = Input::get('term');

        $results = array();

        $queries = DB::table('Cities')
            ->where('name', 'like', $term.'%')
            ->take(20)->get();

        foreach ($queries as $query)
        {
            $results[] = [
                'value' => $query->name,
                'lat' => $query->latitude,
                'long' => $query->longitude];
        }
        return response()->json($results);
    }

    public function map()
    {
        $lat = Input::get('lat');
        $long = Input::get('long');

        $locations = DB::select("SELECT  name, latitude, longitude, ( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$long.") )
          + sin( radians(".$lat.") ) * sin( radians( latitude ) ) ) )
          AS distance FROM cities HAVING distance < 5000 ORDER BY distance LIMIT 0 , 20");

        return $locations;
    }
}

