<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PersonalVerificationDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_verification_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->string('status')->default('Valid');
            $table->timestamps();
        });
        $data = array(
            array('domain'=>'googlemail.com'),
            array('domain'=>'live.com'),
            array('domain'=>'gmail.com'),
            array('domain'=>'outlook.com'),
            array('domain'=>'hotmail.com'),
            array('domain'=>'icloud.com'),
            array('domain'=>'zoho.com'),
            array('domain'=>'msn.com'),
            array('domain'=>'tutanota.com'),
            array('domain'=>'yandex.com'),
            array('domain'=>'fastmail.com'),
            array('domain'=>'gmx.com'),
            array('domain'=>'gmx.us'),
            array('domain'=>'freemailnow.net'),
            array('domain'=>'vmailcloud.com'),
            array('domain'=>'mailhost.top'),
            array('domain'=>'fastmailnow.com'),
            array('domain'=>'vmailpro.net'),
            array('domain'=>'vmailcloud.net'),
            array('domain'=>'protonmail.com'),
            array('domain'=>'tutanota.de'),
            array('domain'=>'tmailservices.com'),
            array('domain'=>'hushmail.com'),
            array('domain'=>'lycos.com'),
            array('domain'=>'yandex.mail'),

            
 
        );
        DB::table('personal_verification_domains')->insert($data);
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_verification_domains');
    }
}
