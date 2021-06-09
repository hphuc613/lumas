<?php

use Illuminate\Database\Seeder;
use Modules\Member\Model\Member;

class MemberSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        factory(Member::class, 4)->create();
    }
}
