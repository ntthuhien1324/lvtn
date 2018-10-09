<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTietHocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiet_hoc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();
            $table->timestamp('gio_bat_dau')->nullable();
            $table->timestamp('gio_ket_thuc')->nullable();
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
        Schema::dropIfExists('tiet_hoc');
    }
}
