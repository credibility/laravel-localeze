<?php

use Illuminate\Database\Migrations\Migration;

class CreateLocalezeCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localeze_categories', function($table)
        {
            $table->increments('id');
            $table->string('sic_code')->index();
            $table->string('category_name');
            $table->integer('category_id');
            $table->timestamps();
            $table->unique(array('sic_code'));
        });

        $json = file_get_contents(__DIR__."/../localeze_categories.json");
        DB::table('localeze_categories')->insert(json_decode($json, true));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('localeze_categories');
    }

}