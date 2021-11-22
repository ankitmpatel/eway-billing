<?php

namespace App\Listeners;

use App\Events\ExtendEwayBillProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Libraries\MasterIndia\EwayBills;

class ExtendEwayBillMasterIndia implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ExtendEwayBillProcessed  $event
     * @return void
     */
    public function handle(ExtendEwayBillProcessed $event)
    {
        $ewayObj = new EwayBills();
        $ewayObj->extendEwayBill($event->eWayData);
    }
}
