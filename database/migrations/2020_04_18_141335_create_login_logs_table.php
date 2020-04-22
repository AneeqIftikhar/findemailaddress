<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Victorybiz\GeoIPLocation\GeoIPLocation;
use App\User;
class CreateLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->string('ip')->nullable();
            $table->string('country')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        $users=User::whereNotNull('user_agent')->get();

        for($i=0;$i<count($users);$i++)
        {
            $agent=json_decode($users[$i]->user_agent);
            $user_agent['ip']=$agent->ip;
            $user_agent['browser']=$agent->browser;
            $user_agent['browser_version']=$agent->browser_version;
            $user_agent['platform']=$agent->platform;
            $user_agent['platform_version']=$agent->platform_version;
            $user_agent['device']=$agent->device;
            $geoip = new GeoIPLocation(); 
            $geoip->setIP($agent->ip);
            $user_agent['city']=$geoip->getCity();
            $user_agent['region']=$geoip->getRegion();
            $user_agent['country']=$geoip->getCountry();
            $user_agent['country_code']=$geoip->getCountryCode();
            $user_agent['continent']=$geoip->getContinent();
            $user_agent['location']=$geoip->getLocation();
            $users[$i]->user_agent=json_encode($user_agent);
            $users[$i]->save();

        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_logs');
    }
}
