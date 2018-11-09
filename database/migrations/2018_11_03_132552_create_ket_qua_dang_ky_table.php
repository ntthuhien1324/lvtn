<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaDangKyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_dang_ky', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_sv');
            $table->string('id_hoc_phan_dang_ky');
            $table->string('id_mon_hoc');
            $table->string('da_hoc');
            $table->string('thoi_gian_dang_ky');
            $table->string('diem_giua_ky');
            $table->string('diem_cuoi_ky');
            $table->string('tl_diem_giua_ky');
            $table->string('tl_diem_cuoi_ky');
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
        Schema::dropIfExists('ket_qua_dang_ky');
    }
}
