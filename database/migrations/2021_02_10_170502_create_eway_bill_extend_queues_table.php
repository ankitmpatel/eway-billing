<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEwayBillExtendQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eway_bill_extend_queues', function (Blueprint $table) {
            $table->id();
            $table->string('eway_bill_number')->index();
            $table->string('batch_id')->index();
            $table->dateTime('batch_datetime', $precision = 0);
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->longText('original_request')->nullable();
            $table->enum('status', ['pending', 'failure', 'success']);
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
        Schema::dropIfExists('eway_bill_extend_queues');
    }
}
