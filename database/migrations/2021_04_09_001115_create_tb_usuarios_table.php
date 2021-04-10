<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_usuarios', function (Blueprint $table) {
            $table->bigIncrements('iUsuario_id');
            $table->string("sUsuario_nome", 100);
            $table->string("sUsuario_cpf", 14)->unique();
            $table->string("sUsuario_email", 50)->unique();
            $table->string("sUsuario_password", 256);
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
        Schema::dropIfExists('tb_usuarios');
        Schema::dropIfExists('tbusuarios');
    }
}
