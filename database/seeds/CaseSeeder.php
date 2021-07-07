<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('cases')->insert([
            'status' => 'active',
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
            'amount' => 10000000,
            'accepted' => false,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam laoreet volutpat odio, quis aliquet nibh vestibulum at. Nulla porttitor sed ante eget finibus. Nulla viverra est odio, in pretium elit pulvinar non. Pellentesque ultricies mauris sit amet justo feugiat, at vehicula neque mollis. Nunc dignissim mollis libero, quis hendrerit erat lobortis nec. Mauris vitae molestie turpis. Ut urna neque, vulputate sit amet mattis eu, varius consequat odio. Pellentesque in laoreet dui, quis imperdiet lacus.',
            'agreement' => 'Curabitur finibus fringilla nisi ut faucibus. Praesent vulputate nisi nec dolor consectetur pretium. Donec ultricies erat aliquam ante pretium, vel pharetra est suscipit. Nam iaculis purus vitae mi molestie porttitor ut in ante. Aenean mollis ornare enim, et bibendum metus suscipit ullamcorper. Donec nec mattis purus. Quisque euismod lorem quis felis varius, eget scelerisque risus dictum. In eros purus, volutpat in odio vitae, accumsan euismod velit. Nam pulvinar lectus urna, sed faucibus felis varius a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id magna eget odio ultricies viverra. Suspendisse tempor vehicula justo a eleifend. Aliquam tempus ornare lectus ac tempor.',
            'notes' => '',
            'dateAcepted' => Carbon::now(),
            'opener' => 122334455,
            'recipient' => 122336677,
            'mediator' => 122338899,
        ]);
        $userMediator = Factory(App\User::class)->create([
            'name' => 'Rogelio',
            'lastname' => 'Piedra',
            'phone' => '55554444',
            'cedula' => '122338899',
            'aboutme' => 'hi im the mediator',
            'email' => 'mediator@test.com',
            'email_verified_at' => now(),
            'api_token' => Str::random(60),
            'password' => '$2y$12$.pu1uic1mqBiTUrrDqhHheZGpBnPnQRVBACSi4mZdqm7npI1U4dIK', // password
            'remember_token' => Str::random(10),
        ]);
        $userMediator->assignRole('mediator');

        $userOpener = Factory(App\User::class)->create([
            'name' => 'Juan',
            'lastname' => 'Gama',
            'phone' => '44446666',
            'cedula' => '122334455',
            'aboutme' => 'hi im the opener',
            'email' => 'opener@test.com',
            'email_verified_at' => now(),
            'api_token' => Str::random(60),
            'password' => '$2y$12$.pu1uic1mqBiTUrrDqhHheZGpBnPnQRVBACSi4mZdqm7npI1U4dIK', // password
            'remember_token' => Str::random(10),
        ]);
        $userOpener->assignRole('client');

        $userRecipient = Factory(App\User::class)->create([
            'name' => 'Juana',
            'lastname' => 'Gama',
            'phone' => '77778888',
            'cedula' => '122336677',
            'aboutme' => 'hi im the userRecipient',
            'email' => 'recipient@test.com',
            'email_verified_at' => now(),
            'api_token' => Str::random(60),
            'password' => '$2y$12$.pu1uic1mqBiTUrrDqhHheZGpBnPnQRVBACSi4mZdqm7npI1U4dIK', // password
            'remember_token' => Str::random(10),
        ]);
        $userRecipient->assignRole('client');
    }
}
