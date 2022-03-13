<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faltas', function (Blueprint $table) {
            $table->id();
            $table->integer("id_professor");
            $table->integer("id_user");
            $table->date("data_falta");
            $table->tinyInteger("horario");
            $table->char("periodo", 1)->comment("M\T\N");
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
        Schema::dropIfExists('faltas');
    }
}
