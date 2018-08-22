<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('geonameid');
            $table->string('name', '200')->charset('utf8')->collation('utf8_unicode_ci');
            $table->string('asciiname', '200')->charset('utf8')->collation('utf8_unicode_ci');
            $table->string('alternatenames', '10000');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->char('featureclass', '1');
            $table->string('featurecode', '10');
            $table->string('countrycode', '2');
            $table->string('cc2', '200');
            $table->string('admin1code', '20');
            $table->string('admin2code', '80');
            $table->string('admin3code', '20');
            $table->string('admin4code', '20');
            $table->bigInteger('population');
            $table->integer('elevation');
            $table->integer('dem');
            $table->string('timezone', '40');
            $table->dateTime('modificationdate');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('city');
    }
}
