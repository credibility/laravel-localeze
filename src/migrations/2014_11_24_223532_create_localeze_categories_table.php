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
        if(!Schema::hasTable('localeze_categories')) {
            Schema::create('localeze_categories', function ($table) {
                $table->string('sic_code')->index();
                $table->string('category_name');
                $table->integer('category_id');
                $table->unique(array('sic_code'));
            });
        }

        $json = file_get_contents(base_path('vendor/credibility/laravel-localeze/src/localeze_categories.json'));
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