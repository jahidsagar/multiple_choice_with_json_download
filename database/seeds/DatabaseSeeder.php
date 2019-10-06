<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
    	$admin = App\Role::create([
            'name'=>'admin'
        ]);
        $users = App\Role::create([
            'name'=>'user'
        ]);
        $notApproved = App\Role::create([
            'name'=>'block'
        ]);
        factory(\App\User::class)->create();
    }
}
