<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiLeDiemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ti_le_diem', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();
            $table->integer('ti_le_giua_ky')->nullable();
            $table->integer('ti_le_cuoi_ky')->nullable();
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
        Schema::dropIfExists('ti_le_diem');
    }
}
