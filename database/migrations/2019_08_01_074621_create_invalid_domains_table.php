<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateInvalidDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->timestamps();
        });
        $data = array(
            array('domain'=>'gmail.com'),
            array('domain'=>'googlemail.com'),
            array('domain'=>'live.com'),
            array('domain'=>'gmail.com'),
            array('domain'=>'outlook.com'),
            array('domain'=>'hotmail.com'),
            array('domain'=>'microsoft.com'),
            array('domain'=>'yahoo.com'),
            array('domain'=>'icloud.com'),
            array('domain'=>'zoho.com'),
            array('domain'=>'aol.com'),
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
            array('domain'=>'direct-mail.top'),
            array('domain'=>'getvmail.net'),
            array('domain'=>'tmailservices.com'),
            array('domain'=>'provmail.net'),
            array('domain'=>'desoz.com'),
            array('domain'=>'hellomail.fun'),
            array('domain'=>'in0hio.com'),
            array('domain'=>'ln0hio.com'),
            array('domain'=>'zoutlook.com'),
            array('domain'=>'eyandex.ru'),
            array('domain'=>'ashotmail.com'),
            array('domain'=>'901.email'),
            array('domain'=>'iigmail.com'),
            array('domain'=>'montokop.pw'),
            array('domain'=>'smart-email.me'),
            array('domain'=>'lalala.fun'),
            array('domain'=>'yevme.com'),
            array('domain'=>'maildrop.cc'),    
        );
        DB::table('invalid_domains')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invalid_domains');
    }
}
