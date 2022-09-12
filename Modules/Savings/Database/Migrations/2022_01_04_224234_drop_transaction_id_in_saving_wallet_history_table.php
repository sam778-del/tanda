<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTransactionIdInSavingWalletHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('savings_wallet_history', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('savings_wallet_history', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->constrained();
        });
    }
}
