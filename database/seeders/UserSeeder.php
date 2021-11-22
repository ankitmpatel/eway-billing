<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'name'              => 'Ankit Patel',
            'email'                   => 'ankit@elitechsystems.com'
          ],
          [
            'password'                => bcrypt('password'),
          ]);
    }
}
