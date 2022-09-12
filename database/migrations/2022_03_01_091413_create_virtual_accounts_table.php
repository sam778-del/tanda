<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->string("currency")->nullable();
            $table->float("balance")->nullable();
            $table->string("status")->nullable();
            $table->string("type")->nullable();
            $table->string("bank_name")->nullable();
            $table->string("bank_code")->nullable();
            $table->string("kyc_level")->nullable();
            $table->string("account_holder")->nullable();
            $table->string("account_name")->nullable();
            $table->string("account_number")->nullable();
            $table->foreignId("user_id")->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('virtual_accounts');
    }
}
