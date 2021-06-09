<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberCoursesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('member_courses', function(Blueprint $table){
            $table->id();
            $table->string('code');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('voucher_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('deduct_quantity')->default(0);
            $table->smallInteger('status')->default(1);
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('member_courses');
    }
}
