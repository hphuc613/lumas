<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Setting\Model\AppointmentSetting;
use Modules\Setting\Model\CommissionRateSetting;

class CreateSettingsTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('settings', function(Blueprint $table){
            $table->id();
            $table->string('key');
            $table->string('value')->nullable();
        });

        DB::table('roles')->insert([
            'key'   => AppointmentSetting::TIMER,
            'value' => 60
        ]);

        DB::table('roles')->insert([
            'key'   => CommissionRateSetting::COMPANY_INCOME,
            'value' => '[4,2]'
        ]);

        DB::table('roles')->insert([
            'key'   => CommissionRateSetting::PERSON_INCOME,
            'value' => '[3]'
        ]);


        DB::table('roles')->insert([
            'key'   => CommissionRateSetting::SERVICE_RATE,
            'value' => 0
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('settings');
    }
}
