<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_transaction_id')->constrained();
            $table->text("user_request_payload")->nullable();
            $table->text("user_response_payload")->nullable();
            $table->text("processor_request_payload")->nullable();
            $table->text("processor_response_payload")->nullable();
            $table->text("requery_payload")->nullable();
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
        Schema::dropIfExists('bill_transaction_logs');
    }
}
