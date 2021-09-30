<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAppointmentsTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('appointments', function(Blueprint $table){
            $table->unsignedBigInteger('instrument_id')->after('type')->nullable();
            $table->unsignedBigInteger('room_id')->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('appointments', function(Blueprint $table){
            $table->dropColumn('room_id');
            $table->dropColumn('instrument_id');
        });
    }
}
