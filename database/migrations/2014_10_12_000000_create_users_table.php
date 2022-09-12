<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_no')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->date('dob')->nullable();
            $table->text('job_type')->nullable();
            $table->text('department')->nullable();
            $table->text('employment_type')->nullable();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('pin_code')->nullable();
            $table->text('app_code')->nullable();
            $table->string('image')->nullable();
            $table->string('employer_id')->nullable();
            $table->string('status')->default(User::DISABLE);
            $table->float('basic_pay')->nullable()->default(0.00);
            $table->float('housing_allowance')->nullable()->default(0.00);
            $table->float('transport_allowance')->nullable()->default(0.00);
            $table->timestamp('blocked_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
