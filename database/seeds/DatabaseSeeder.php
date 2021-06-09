<?php

use Illuminate\Database\Seeder;
use Modules\Course\Model\Course;
use Modules\Course\Model\CourseCategory;
use Modules\Member\Model\MemberType;
use Modules\Role\Model\Role;
use Modules\Service\Model\Service;
use Modules\Service\Model\ServiceType;
use Modules\Store\Model\Store;

class DatabaseSeeder extends Seeder{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(MemberTypeSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(StoreSeeder::class);
    }
}

class RoleSeeder extends Seeder{
    public function run(){
        Role::insert([['name' => 'Staff']]);
    }
}

class MemberTypeSeeder extends Seeder{
    public function run(){
        MemberType::insert([['name' => 'Default Client'], ['name' => 'Vip']]);
    }
}

class ServiceSeeder extends Seeder{
    public function run(){
        ServiceType::insert([
                                ['name' => 'Massage'],
                                ['name' => 'Shampoo']
                            ]);
        Service::insert([
                            [
                                'name'    => 'Face massage',
                                'price'   => 30000,
                                'type_id' => 1
                            ],
                            [
                                'name'    => 'Body massage',
                                'price'   => 50000,
                                'type_id' => 1
                            ],
                            [
                                'name'    => 'Shampoo normal',
                                'price'   => 20000,
                                'type_id' => 2
                            ],
                            [
                                'name'    => 'Shampoo herbal therapy',
                                'price'   => 50000,
                                'type_id' => 2
                            ],
                        ]);
    }
}

class CourseSeeder extends Seeder{
    public function run(){
        CourseCategory::insert([
                                   ['name' => 'Yoga'],
                                   ['name' => 'Aerobics']
                               ]);
        Course::insert([
                           [
                               'name'        => 'Yoga',
                               'price'       => 300000,
                               'category_id' => 1
                           ],
                           [
                               'name'        => 'Aerobics',
                               'price'       => 350000,
                               'category_id' => 2
                           ],
                       ]);
    }
}

class StoreSeeder extends Seeder{
    public function run(){
        Store::insert([
                          [
                              'name'            => 'US Store',
                              'address'         => 'America',
                              'open_close_time' => '9h-18h',
                          ],
                          [
                              'name'            => 'HK Store',
                              'address'         => 'Hong Kong',
                              'open_close_time' => '8h-17h',
                          ]
                      ]);
    }
}
