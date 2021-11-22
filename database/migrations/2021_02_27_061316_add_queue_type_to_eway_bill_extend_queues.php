<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQueueTypeToEwayBillExtendQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eway_bill_extend_queues', function (Blueprint $table) {
            $table->string('queue_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eway_bill_extend_queues', function (Blueprint $table) {
            $table->dropColumn('queue_type');
        });
    }
}
