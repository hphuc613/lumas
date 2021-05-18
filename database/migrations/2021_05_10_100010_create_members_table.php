<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('members', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->timestamp('birthday')->nullable();
            $table->integer('sex')->default(0);
            $table->string('avatar')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address', 200)->nullable();
            $table->text('contact_info')->nullable();
            $table->string('username');
            $table->string('password');
            $table->integer('status')->default(1);
            $table->string('remember_token')->nullable();
            $table->string('email_verified_at')->nullable();
            $table->unsignedInteger('type_id')->default(1);
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
        Schema::dropIfExists('members');
    }
}
