<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTbUsuariosToTbCarteira extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_carteira', function (Blueprint $table) {
            $table->unsignedBigInteger('iUsuario_id');
            $table->foreign('iUsuario_id')->references('iUsuario_id')->on('tb_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_carteira', function (Blueprint $table) {
            /*$table->dropForeign('iUsuario_id');
            $table->dropColumn('iUsuario_id');*/
        });
    }
}
