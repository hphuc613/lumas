<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('roles', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->smallInteger('status')->default(1);
            $table->text('description')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert(
            [
                'name'        => 'Administrator',
                'status'      => 1,
                'description' => '',
                'created_at'  => '2020-10-15 23:30:41',
                'updated_at'  => '2020-10-20 22:17:19'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('roles');
    }
}
