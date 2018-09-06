<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('event_type');
            $table->string('place');
            $table->mediumText('description');
            $table->date('date');
            $table->time('time');
            $table->double('duration_minutes');
            $table->integer('max_guests_number');
            $table->decimal('geo_lat', 10, 8)->nullable();
            $table->decimal('geo_lng', 11, 8)->nullable();
            $table->timestamp('applications_ends_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->index('date');
            $table->index('time');
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
