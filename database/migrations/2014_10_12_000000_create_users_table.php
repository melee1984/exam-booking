<?php

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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
             $table->string('api_token', 80)->nullable()->unique()->nullable()->default(null);
            $table->bigInteger('role_id')->unsigned()->nullable()->default(0); // 0 guest 1 admin 
            $table->rememberToken();
            $table->timestamps();
            // $table->softDeletes(); // I will not include the softDelete as not part of the creteria
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
