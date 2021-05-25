<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberServicesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('member_services', function(Blueprint $table){
            $table->id();
            $table->string('code');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('voucher_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('deduct_quantity')->default(0);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('member_services');
    }
}
