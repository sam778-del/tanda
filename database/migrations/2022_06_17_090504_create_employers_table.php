<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employer;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('role_id')->default(Employer::ROLE);
            $table->timestamp('email_verified_at')->nullable();
            $table->longText('know_us')->nullable();
            $table->string('photo')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default(Employer::STATUS);
            $table->string('plan_id', 100)->nullable();
            $table->text('bank_name')->nullable();
            $table->text('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('google_id')->nullable();
            $table->boolean('plan_expiry_status')->nullable()->default(false);
            $table->date('plan_expiry_date')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('employers');
    }
}
