<?php

use Illuminate\Database\Migrations\Migration;

class AddUserIdToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function ($table) {
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('categories', function ($table) {
            $table->dropColumn('user_id');
        });
    }

}
