<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTbTipoUsuarioToTbUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('iTipo_usuario_id');
            $table->foreign('iTipo_usuario_id')->references('iTipo_usuario_id')->on('tb_tipo_usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            /*$table->dropForeign('iTipo_usuario_id');
            $table->dropColumn('iTipo_usuario_id');*/
        });
    }
}
