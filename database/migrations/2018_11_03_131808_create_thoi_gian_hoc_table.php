<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThoiGianHocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thoi_gian_hoc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_phong_hoc')->nullable();
            $table->string('id_hoc_phan_dang_ky');
            $table->string('ngay');
            $table->string('gio_bat_dau');
            $table->string('gio_ket_thuc');
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
        Schema::dropIfExists('thoi_gian_hoc');
    }
}
