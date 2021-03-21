<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function($table) {
            $table->string('phone', 30);
            $table->string('pic_phone', 30);
            $table->string('address', 200);
            $table->string('email', 100);
            $table->renameColumn('name','pic_name');
            $table->renameColumn('company','company_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function($table) {
            $table->dropColumn(['type']);
        });
    }
}
