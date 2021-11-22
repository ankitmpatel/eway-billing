<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Libraries\MasterIndia\EwayBills;
use App\Libraries\Helpers\Helper;
use App\Models\EwayDownloadQueue;

class DownloadEwayBillJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $gstin = env('GSTIN');
        $documentNumber = '';
        $documentDate = $this->date;
        $ewayObj = new EwayBills();
        $eWayBills = $ewayObj->getEwayBillLists($documentNumber, $gstin, $documentDate, $generateStatus=1, $page=1, $limit=20000);
        foreach($eWayBills as $e){
            if(isset($e['eway_bill_number'])){
                EwayDownloadQueue::updateOrCreate(
                    ['eway_bill_number'=>$e['eway_bill_number'],
                    'eway_bill_date'=>$e['eway_bill_date']
                    ],
                    ['eway_bill_number'=>$e['eway_bill_number'],
                     'eway_bill_date'=>$e['eway_bill_date'],
                     'eway_bill_valid_date'=>$e['eway_bill_valid_date'],
                     'eway_bill_valid_date_unix'=>Helper::dateFormatUnixDateTime($e['eway_bill_valid_date']),
                     'request'=>json_encode([$documentNumber, $gstin, $documentDate, $generateStatus=1, $page=1, $limit=20000]),
                     'response'=>json_encode($e)
                    ]
                );
            }
            
        }
    }
}
