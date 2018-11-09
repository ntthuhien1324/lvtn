<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLopHocPhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lop_hoc_phan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_mon_hoc')->nullable();
            $table->string('id_gv')->nullable();
            $table->string('id_dot_dang_ky')->nullable();
            $table->string('sl_hien_tai')->nullable();
            $table->string('sl_max')->nullable();
            $table->string('sl_min')->nullable();
            $table->string('ngay_bat_dau')->nullable();
            $table->string('ngay_ket_thuc')->nullable();
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
        Schema::dropIfExists('lop_hoc_phan');
    }
}
