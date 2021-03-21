<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('company_id');
            $table->bigInteger('user_id');
            $table->text('comment');
            $table->string('status', 100);
            $table->decimal('amount', $precision = 20, $scale = 2);
            $table->dateTime('created_at', $precision = 0);
            $table->dateTime('updated_at', $precision = 0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
    }
}
