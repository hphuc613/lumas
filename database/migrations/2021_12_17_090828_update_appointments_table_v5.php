<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAppointmentsTableV5 extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('appointments', function(Blueprint $table){
            $table->string('instrument_id')->default('[]')->change();
            $table->string('room_id')->default('[]')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('appointments', function(Blueprint $table){
            $table->unsignedBigInteger('instrument_id')->change();
            $table->unsignedBigInteger('room_id')->change();
        });
    }
}
