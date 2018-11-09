<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;

class CreateSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('settings.table', 'settings'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace', 64)->index();
            $table->string('key', 64)->index();
            $table->longText('value')->nullable();
            $table->boolean('autoload')->index();
            $table->unique(['namespace', 'name'], 'namespace_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::get('settings.table', 'settings'));
    }
}
