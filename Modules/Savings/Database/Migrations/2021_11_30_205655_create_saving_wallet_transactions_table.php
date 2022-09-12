<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Savings\Models\SavingsWallet;

class CreateSavingWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('savings_wallet_id');
            $table->foreign('savings_wallet_id')->references('id')->on('savings_wallet');
            $table->foreignId('user_id')->constrained();
            $table->double("amount");
            $table->string("transaction_ref", 191)->unique();
            $table->text("narration")->nullable();
            $table->string("status", 191)->default("pending")->comment("pending,success,failed,reversed");
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
        Schema::dropIfExists('saving_wallet_transactions');
    }
}
