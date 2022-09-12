<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//Mono Changes
class CreatePhysicalCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_cards', function (Blueprint $table) {
            $table->id();
            $table->boolean("disposable")->nullable();
            $table->string("status")->nullable();
            $table->string("type")->nullable();
            $table->string("currency")->nullable();
            $table->string("brand")->nullable();
            $table->string("card_number")->nullable();
            $table->string("card_pan")->nullable();
            $table->string("cvv")->nullable();
            $table->string("expiry_month")->nullable();
            $table->string("expiry_year")->nullable();
            $table->string("last_four")->nullable();
            $table->string("name_on_card")->nullable();
            $table->float("balance")->nullable();
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
        Schema::dropIfExists('physical_cards');
    }
}
