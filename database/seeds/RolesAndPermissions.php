<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Reset cached roles and permissions
         app()[PermissionRegistrar::class]->forgetCachedPermissions();

         // create permissions
         Permission::create(['name' => 'create cases']);
         Permission::create(['name' => 'edit cases']);
         Permission::create(['name' => 'delete cases']);
         Permission::create(['name' => 'publish cases']);
         Permission::create(['name' => 'unpublish cases']);
 
         // create roles and assign existing permissions
         $role1 = Role::create(['name' => 'client']);
         $role1->givePermissionTo('create cases');
         $role1->givePermissionTo('edit cases');

         $role2 = Role::create(['name' => 'company']);
         $role2->givePermissionTo('create cases');
         $role2->givePermissionTo('edit cases');

         $role3 = Role::create(['name' => 'mediator']);
         $role3->givePermissionTo('create cases');
         $role3->givePermissionTo('edit cases');
         $role3->givePermissionTo('delete cases');
 
         $role4 = Role::create(['name' => 'admin']);
         $role4->givePermissionTo('create cases');
         $role4->givePermissionTo('edit cases');
         $role4->givePermissionTo('delete cases');
 
         $role5 = Role::create(['name' => 'super-admin']);
         // gets all permissions via Gate::before rule; see AuthServiceProvider
 
         // create demo users
        //  $user = Factory(App\User::class)->create([
        //      'name' => 'Example User',
        //      'email' => 'test@example.com',
        //  ]);
        //  $user->assignRole($role1);
 
        //  $user = Factory(App\User::class)->create([
        //      'name' => 'Example Admin User',
        //      'email' => 'admin@example.com',
        //  ]);
        //  $user->assignRole($role2);
 
         $user = Factory(App\User::class)->create([
             'name' => 'Augusto',
             'lastname' => 'Valerio',
             'phone' => '88361344',
             'cedula' => '113570739',
             'aboutme' => 'hi im the admin',
             'email' => 'auguvalerio@gmail.com',
             'email_verified_at' => now(),
             'api_token' => Str::random(60),
             'password' => '$2y$12$.pu1uic1mqBiTUrrDqhHheZGpBnPnQRVBACSi4mZdqm7npI1U4dIK', // password
             'remember_token' => Str::random(10),
         ]);
         $user->assignRole($role5);
    }
}
