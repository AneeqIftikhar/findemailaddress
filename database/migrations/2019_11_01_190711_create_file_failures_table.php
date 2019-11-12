<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileFailuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_failures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_file_id')->unsigned();
            $table->foreign('user_file_id')->references('id')->on('user_files')->onDelete('cascade');
            $table->integer('row')->nullable();
            $table->text('attribute')->nullable();
            $table->text('errors')->nullable();
            $table->text('values')->nullable();
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
        Schema::dropIfExists('file_failures');
    }
}
