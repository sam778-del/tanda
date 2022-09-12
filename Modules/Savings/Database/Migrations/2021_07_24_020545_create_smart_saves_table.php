<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartSavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_saves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->double("amount")->nullable();
            $table->string("name");
            $table->date("payback_date");
            $table->string("payment_method");
            $table->string("status");

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smart_saves');
    }
}
