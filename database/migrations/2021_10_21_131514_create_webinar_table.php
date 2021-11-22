<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 255);
            $table->enum('jenis', ['webinar']);
            $table->string('total_skp', 255);
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('tempat', 255);
            $table->string('panitia', 255);
            $table->string('kontak', 255);
            $table->string('thumbnail', 255);
            $table->string('brosur', 255);
            $table->string('konten', 255);
            $table->decimal('biaya',15,2);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('webinar');
    }
}
