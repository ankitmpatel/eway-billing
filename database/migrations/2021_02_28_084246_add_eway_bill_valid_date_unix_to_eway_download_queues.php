<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEwayBillValidDateUnixToEwayDownloadQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eway_download_queues', function (Blueprint $table) {
            $table->string('eway_bill_valid_date_unix')->after('eway_bill_valid_date')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eway_download_queues', function (Blueprint $table) {
            $table->dropColumn('eway_bill_valid_date_unix');
        });
    }
}
