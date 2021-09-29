<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMembersTableV2 extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('members', function(Blueprint $table){
            $table->string('referrer')->after('type_id')->nullable();
            $table->text('how_to_know')->after('type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('members', function(Blueprint $table){
            $table->dropColumn('referrer');
            $table->dropColumn('how_to_know');
        });
    }
}
