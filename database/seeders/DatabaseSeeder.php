<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\TransporterSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\EwayBillExtendReasonsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(TransporterSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(EwayBillExtendReasonsSeeder::class);
    }
}
