<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSinhVienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sinh_vien', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mssv')->nullable();
            $table->string('ho_ten')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('id_lop')->nullable();
            $table->string('id_status')->nullable();
            $table->string('id_nam_hoc')->nullable();
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
        Schema::dropIfExists('sinh_vien');
    }
}
