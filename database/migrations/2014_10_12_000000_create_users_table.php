<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 

    public function up()
    {
        Schema::create('tb_users', function (Blueprint $table) {
            $table->bigIncrements('idUser')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('token');
            $table->integer('idStatusKf');
            $table->integer('idProfileKf');
            $table->integer('idCompanyKf');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cuitRepresentative');
            $table->string('tokenPAEC');
            $table->string('profileAFIP');
            $table->integer('representativeASSOCIATIVE')->default(0);
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
        Schema::dropIfExists('tb_users');
    }
}
