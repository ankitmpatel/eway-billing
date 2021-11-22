<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EwayBillExtendReasons;

class EwayBillExtendReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	EwayBillExtendReasons::updateOrCreate(
			['reason_name' => 'Natural Calamity'],
			['reason_name' => 'Natural Calamity']
		  );

    	EwayBillExtendReasons::updateOrCreate(
			['reason_name' => 'Law and Order Situation'],
			['reason_name' => 'Law and Order Situation']
		  );

    	EwayBillExtendReasons::updateOrCreate(
			['reason_name' => 'Transhipment'],
			['reason_name' => 'Transhipment']
		  );

      EwayBillExtendReasons::updateOrCreate(
        ['reason_name' => 'Accident'],
        ['reason_name' => 'Accident']
      );
      
        EwayBillExtendReasons::updateOrCreate(
            ['reason_name' => 'Others'],
            ['reason_name' => 'Others']
          );
    }
}
