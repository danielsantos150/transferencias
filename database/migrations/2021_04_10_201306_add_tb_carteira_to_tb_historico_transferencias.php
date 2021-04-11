<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTbCarteiraToTbHistoricoTransferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_historico_transferencias', function (Blueprint $table) {
            $table->unsignedBigInteger('iCarteira_fonte_id');
            $table->foreign('iCarteira_fonte_id')->references('iCarteira_id')->on('tb_carteira');
            $table->unsignedBigInteger('iCarteira_destino_id');
            $table->foreign('iCarteira_destino_id')->references('iCarteira_id')->on('tb_carteira');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_historico_transferencias', function (Blueprint $table) {
            /*$table->dropForeign('iCarteira_fonte_id');
            $table->dropColumn('iCarteira_fonte_id');
            $table->dropForeign('iCarteira_destino_id');
            $table->dropColumn('iCarteira_destino_id');*/
        });
    }
}
