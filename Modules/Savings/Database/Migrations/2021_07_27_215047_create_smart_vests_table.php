<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartVestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_vests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->double("amount")->nullable();
            $table->bigInteger("duration");
            $table->date("initial_payment");
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
        Schema::dropIfExists('smart_vests');
    }
}
