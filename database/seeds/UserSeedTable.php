<?php

use Illuminate\Database\Seeder;
class UserSeedTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@aii.co.id',
            'password' => bcrypt('admin'),
            ]);
            DB::table('users')->insert([
            'name' => 'finance1',
            'email' => 'finance1@aii.co.id',
            'password' => bcrypt('finance'),
            ]);
            DB::table('users')->insert([
            'name' => 'finance2',
            'email' => 'finance2@aii.co.id',
            'password' => bcrypt('finance'),
            ]);
            DB::table('users')->insert([
            'name' => 'finance3',
            'email' => 'finance3@aii.co.id',
            'password' => bcrypt('finance'),
            ]);
            DB::table('users')->insert([
            'name' => 'ppic1',
            'email' => 'ppic1@aiia.co.id',
            'password' => bcrypt('ppic'),
            ]);
            DB::table('users')->insert([
            'name' => 'ppic2',
            'email' => 'ppic2@aii.co.id',
            'password' => bcrypt('ppic'),
            ]);
            DB::table('users')->insert([
            'name' => 'ppic3',
            'email' => 'ppic3@aii.co.id',
            'password' => bcrypt('ppic'),
            ]);
            
    }
}
