<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransporterVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transporter_vehicle', function (Blueprint $table) {
            $table->foreignId('transporter_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
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
        Schema::dropIfExists('transporter_vehicle');
    }
}
