<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\State;

class StateSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    if(DB::table('states')->count() < 1 ){
      DB::table('states')->delete();
      $states = [
          ['name' => "JAMMU AND KASHMIR"],
          ['name' => "HIMACHAL PRADESH"],
          ['name' => "PUNJAB"],
          ['name' => "CHANDIGARH"],
          ['name' => "UTTARAKHAND"],
          ['name' => "HARYANA"],
          ['name' => "DELHI"],
          ['name' => "RAJASTHAN"],
          ['name' => "UTTAR PRADESH"],
          ['name' => "BIHAR"],
          ['name' => "SIKKIM"],
          ['name' => "ARUNACHAL PRADESH"],
          ['name' => "NAGALAND"],
          ['name' => "MANIPUR"],
          ['name' => "MIZORAM"],
          ['name' => "TRIPURA"],
          ['name' => "MEGHALAYA"],
          ['name' => "ASSAM"],
          ['name' => "WEST BENGAL"],
          ['name' => "JHARKHAND"],
          ['name' => "ORISSA"],
          ['name' => "CHHATTISGARH"],
          ['name' => "MADHYA PRADESH"],
          ['name' => "GUJARAT"],
          ['name' => "DAMAN AND DIU"],
          ['name' => "DADAR AND NAGAR HAVELI"],
          ['name' => "MAHARASHTRA"],
          ['name' => "ANDHRA PRADESH"],
          ['name' => "KARNATAKA"],
          ['name' => "GOA"],
          ['name' => "LAKSHADWEEP"],
          ['name' => "KERALA"],
          ['name' => "TAMIL NADU"],
          ['name' => "PONDICHERRY"],
          ['name' => "ANDAMAN AND NICOBAR"],
          ['name' => "TELANGANA"],
          ['name' => "OTHER TERRITORY"],
          ['name' => "OTHER COUNTRY"]
      ];
      DB::table('states')->insert($states);
    }
   
  }
}
