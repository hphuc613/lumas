<?php

use Illuminate\Database\Seeder;
use Modules\User\Model\User;

class UserSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        factory(User::class, 4)->create();
    }
}
