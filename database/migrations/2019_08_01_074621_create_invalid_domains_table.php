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
            array('domain'=>'inappmail.com'),
            array('domain'=>'app-mailer.com'),
            array('domain'=>'33mail.com'),
            array('domain'=>'urhen.com'),
            array('domain'=>'phaantm.de'),
            array('domain'=>'trash-mail.com'),
            array('domain'=>'you-spam.com'),
            array('domain'=>'re-gister.com'),
            array('domain'=>'fake-box.com'),
            array('domain'=>'trash-me.com'),
            array('domain'=>'opentrash.com'),
            array('domain'=>'mail.qmeta.net'),
            array('domain'=>'Hatmail.ir'),
            array('domain'=>'Gmeil.site'),
            array('domain'=>'Outlok.site'),
            array('domain'=>'aiwip.org'),
            array('domain'=>'ciwip.org'),
            array('domain'=>'iiwip.org'),
            array('domain'=>'nziwip.org'),
            array('domain'=>'ukiwip.org'),
            array('domain'=>'wipia.org'),
            array('domain'=>'10m.email'),
            array('domain'=>'anonmails.de'),
            array('domain'=>'fakeinbox.com'),
            array('domain'=>'africamel.net'),
            array('domain'=>'brusseler.com'),
            array('domain'=>'emailasso.net'),
            array('domain'=>'europamel.net'),
            array('domain'=>'francemel.fr'),
            array('domain'=>'inmano.com'),
            array('domain'=>'lavache.com'),
            array('domain'=>'mailo.com'),
            array('domain'=>'monemail.com'),
            array('domain'=>'net-c.be'),
            array('domain'=>'net-c.ca'),
            array('domain'=>'net-c.cat'),
            array('domain'=>'net-c.com'),
            array('domain'=>'net-c.es'),
            array('domain'=>'net-c.fr'),
            array('domain'=>'net-c.it'),
            array('domain'=>'net-c.lu'),
            array('domain'=>'net-c.nl'),
            array('domain'=>'net-c.pl'),
            array('domain'=>'netc.eu'),
            array('domain'=>'netc.fr'),
            array('domain'=>'netc.it'),
            array('domain'=>'netc.lu'),
            array('domain'=>'netc.pl'),
            array('domain'=>'netcmail.com'),
            array('domain'=>'netcourrier.com'),
            array('domain'=>'perso.be'),
            array('domain'=>'vfemail.net'),
            array('domain'=>'sharedmailbox.org'),
            array('domain'=>'inboxproxy.com'),
            array('domain'=>'dacoolest.com'),
            array('domain'=>'emailproxsy.com'),
            array('domain'=>'mailproxsy.com'),
            array('domain'=>'throwawayemailaddress.com'),
            array('domain'=>'yopmail.com'),
            array('domain'=>'thtt.us'),
            array('domain'=>'20mail.it'),
            array('domain'=>'mt2015.com'),
            array('domain'=>'vmani.com'),
            array('domain'=>'0box.eu'),
            array('domain'=>'contbay.com'),
            array('domain'=>'damnthespam.com'),
            array('domain'=>'kurzepost.de'),
            array('domain'=>'objectmail.com'),
            array('domain'=>'proxymail.eu'),
            array('domain'=>'rcpt.at'),
            array('domain'=>'trash-mail.at'),
            array('domain'=>'trashmail.at'),
            array('domain'=>'trashmail.com'),
            array('domain'=>'trashmail.io'),
            array('domain'=>'trashmail.me'),
            array('domain'=>'trashmail.net'),
            array('domain'=>'wegwerfmail.de'),
            array('domain'=>'wegwerfmail.net'),
            array('domain'=>'wegwerfmail.org'),
            array('domain'=>'sharklasers.com'),
            array('domain'=>'guerrillamail.info'),
            array('domain'=>'guerrillamail.biz'),
            array('domain'=>'grr.la'),
            array('domain'=>'guerrillamail.com'),
            array('domain'=>'guerrillamail.de'),
            array('domain'=>'guerrillamail.net'),
            array('domain'=>'guerrillamail.org'),
            array('domain'=>'guerrillamailblock.com'),
            array('domain'=>'pokemail.net'),
            array('domain'=>'spam4.me'),
            
 
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
