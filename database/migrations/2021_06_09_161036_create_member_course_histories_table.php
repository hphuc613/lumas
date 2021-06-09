<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberCourseHistoriesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('member_course_histories', function(Blueprint $table){
            $table->id();
            $table->string('signature');
            $table->unsignedInteger('member_course_id');
            $table->unsignedInteger('appointment_id');
            $table->unsignedInteger('updated_by');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
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
        Schema::dropIfExists('member_course_histories');
    }
}
