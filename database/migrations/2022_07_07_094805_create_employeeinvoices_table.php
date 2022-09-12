<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeeinvoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id',100)->unique()->nullable();
            $table->string('employer_id', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('url')->nullable();
            $table->string('plan_id', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employeeinvoices');
    }
}
