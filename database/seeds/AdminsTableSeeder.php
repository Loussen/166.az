<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = DB ::table( \App\Admin::TABLE ) -> insertGetId( [ 'name' => 'Khayyam Musazada' , 'username' => 'khayyamm' , 'password' => Hash ::make( '12345' ) ] );

        DB ::table( \App\Models\AdminRole::TABLE ) -> insert( [ 'admin_id' => $admin , 'role' => \App\Http\Controllers\Admin\AdminController::ADMIN_ROLE ] );
    }
}
