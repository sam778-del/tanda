<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("vendor");
            $table->string("category");
            $table->string("group_name");
            $table->string("service_code")->nullable();
            $table->string("image")->nullable();
            $table->decimal("amount", 22, 2)->nullable();
            $table->decimal("fee", 10, 2)->nullable();
            $table->text("description")->nullable();
            $table->boolean("status");
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
        Schema::dropIfExists('bills');
    }
}
