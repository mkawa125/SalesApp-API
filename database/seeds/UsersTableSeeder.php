<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Dahabu',
            'middle_name' => 'Dahabu',
            'surname' => 'Dahabu',
            'name' => 'Dahabu Mkawa',
            'phone_number' => '255717495198', 
        	'email' => 'dahabusaidi@gmail.com',
        	'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->name]);

    }
}
