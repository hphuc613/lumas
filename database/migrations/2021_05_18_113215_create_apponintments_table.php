<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApponintmentsTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('appointments', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('location', 400)->nullable();
            $table->timestamp('time')->nullable();
            $table->unsignedInteger('store_id');
            $table->string('service_ids')->nullable();
            $table->string('course_ids')->nullable();
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('user_id');
            $table->integer('status')->default(1);
            $table->string('type')->default('service');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
