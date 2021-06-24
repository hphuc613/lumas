<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->double('basic_salary')->default(0);
            $table->integer('status')->default(1);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });


        DB::table('users')->insert([
                                       'name'       => 'Administrator',
                                       'email'      => 'admin@gmail.com',
                                       'password'   => '$2y$10$Pji4MA/QQD1GTRPpRs2IAOTGfQUelsUGzYlWMDM.iZKHxqSMXRlUu',
                                       'created_at' => '2020-10-15 23:30:41',
                                       'updated_at' => '2020-10-20 22:17:19'
                                   ]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
}
