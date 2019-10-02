<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('ticketit_admin')->default(0);
            $table->boolean('ticketit_agent')->default(0);
        });



        $data=array (
            array(
                'name' => "Support",
                'user_uuid' => '8dbaaa00-ea0e-4aec-89d3-3621b908b09a',
                'password'=>Hash::make('P@kistan1'),
                'email'=>'support@findemailaddress.co',
                'email_verified_at'=>date('Y-m-d H:i:s'),
                'package_id'=>1,
                'created_at' => date('Y-m-d H:i:s'),
                'ticketit_admin'=>true,
                'updated_at' => date('Y-m-d H:i:s')
            ),
        );
        
        DB::table('users')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ticketit_admin', 'ticketit_agent']);
        });
    }
}
