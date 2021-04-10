<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCarteiraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_carteira', function (Blueprint $table) {
            $table->bigIncrements('iCarteira_id');
            $table->decimal('fSaldo_carteira', 15, 2)->default(0.00);
            $table->decimal('fSaldo_bloqueado', 15, 2)->default(0.00);
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
        Schema::dropIfExists('tb_carteira');
    }
}
