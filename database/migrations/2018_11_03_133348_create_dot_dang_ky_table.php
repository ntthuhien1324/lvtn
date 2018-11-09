<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDotDangKyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dot_dang_ky', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();
            $table->string('ngay_bat_dau')->nullable();
            $table->string('ngay_ket_thuc')->nullable();
            $table->string('hoc_ky')->nullable();
            $table->string('tc_max')->nullable();
            $table->string('tc_min')->nullable();
            $table->string('trang_thai')->nullable();
            $table->string('trang_thai_import_diem')->nullable();
            $table->string('trang_thai_sua_diem')->nullable();
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
        Schema::dropIfExists('dot_dang_ky');
    }
}
