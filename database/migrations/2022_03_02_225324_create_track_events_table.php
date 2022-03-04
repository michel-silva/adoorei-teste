<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('track_id');
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');

            $table->dateTime('date_event');
            $table->text('event');
            $table->text('unit')->nullable();
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
        Schema::dropIfExists('track_histories');

    }
}
