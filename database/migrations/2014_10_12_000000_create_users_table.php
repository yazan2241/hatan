<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('firstName');
            $table->string('fatherName');
            $table->string('lastName');
            $table->string('idNumber');
            $table->string('phone');
            $table->string('bornDate');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('section')->default(0);
            $table->integer('hours');
            $table->integer('team');   
            $table->integer('type')->default(0);                
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
        Schema::dropIfExists('users');
    }
};
