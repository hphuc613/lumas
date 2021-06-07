<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberServiceHistoriesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('member_service_histories', function(Blueprint $table){
            $table->id();
            $table->string('signature');
            $table->unsignedInteger('member_service_id');
            $table->unsignedInteger('appointment_id');
            $table->unsignedInteger('updated_by');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('member_service_histories');
    }
}
