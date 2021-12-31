<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Vygeneruj testovacie uživateľske účty.
     *
     * @return void
     */
    public function run(): void
    {
        User::updateOrCreate([
            'name' => 'micho',
            'email' => 'micho@localhost',
        ], [
            'password' => Hash::make('micho_heslo'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::updateOrCreate([
            'name' => 'user',
            'email' => 'user@localhost',
        ], [
            'password' => Hash::make('user_heslo'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
