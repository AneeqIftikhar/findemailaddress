<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('annual_saving_percent')->nullable();
            $table->integer('credits');
            $table->timestamps();
        });
        DB::table('packages')->insert(
            array(
                'name' => "Free",
                'amount' => 0,
                'credits'=>100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            )
        );
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
