<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEwayDownloadQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eway_download_queues', function (Blueprint $table) {
            $table->id();
            $table->string('eway_bill_number')->index();
            $table->string('eway_bill_date');
            $table->string('eway_bill_valid_date');
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eway_download_queues');
    }
}
