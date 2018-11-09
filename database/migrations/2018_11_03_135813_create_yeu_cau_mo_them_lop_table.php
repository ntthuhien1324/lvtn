<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYeuCauMoThemLopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yeu_cau_mo_them_lop', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_user')->nullable();
            $table->string('id_mon_hoc')->nullable();
            $table->string('id_dot_dang_ky')->nullable();
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
        Schema::dropIfExists('yeu_cau_mo_them_lop');
    }
}
