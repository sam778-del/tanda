<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_locks', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->double("amount")->nullable();
            $table->double("interest")->nullable();
            $table->foreignId('user_id')->constrained();
            $table->bigInteger("lock_duration");
            $table->date("payout_date");
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
        Schema::dropIfExists('smart_locks');
    }
}
