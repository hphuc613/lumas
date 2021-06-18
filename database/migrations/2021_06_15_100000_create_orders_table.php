<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('orders', function(Blueprint $table){
            $table->id();
            $table->string('code');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('remarks')->nullable();
            $table->double('total_price')->default(0);
            $table->smallInteger('status')->default(1);
            $table->string('order_type');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('orders');
    }
}
