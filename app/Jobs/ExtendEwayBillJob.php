<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Libraries\MasterIndia\EwayBills;

class ExtendEwayBillJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $eWayData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($eWayData)
    {
        $this->eWayData = $eWayData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ewayObj = new EwayBills();
        $ewayObj->extendEwayBill($this->eWayData);
    }
}
