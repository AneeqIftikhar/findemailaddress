<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('next_package_id')->unsigned();
            $table->foreign('next_package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->bigInteger('current_package_id')->unsigned();
            $table->foreign('current_package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('credits');
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('pending_subscriptions');
    }
}
