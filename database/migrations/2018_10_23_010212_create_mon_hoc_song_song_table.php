<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonHocSongSongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mon_hoc_song_song', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_mon_hoc_1')->nullable();
            $table->string('id_mon_hoc_2')->nullable();
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
        Schema::dropIfExists('mon_hoc_song_song');
    }
}
