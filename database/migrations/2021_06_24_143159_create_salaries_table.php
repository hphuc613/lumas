<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('salaries', function(Blueprint $table){
            $table->id();
            $table->string('month');
            $table->double('basic_salary')->default(0);
            $table->double('sale_commission')->default(0);
            $table->double('service_commission')->default(0);
            $table->double('company_commission')->default(0);
            $table->double('total_commission')->default(0);
            $table->double('total_salary')->default(0);
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('salaries');
    }
}
