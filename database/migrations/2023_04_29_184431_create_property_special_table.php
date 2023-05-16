<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_special', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('location');
            $table->foreignId('users_id')->constrained('users');
            $table->string('typeofproperty');
            $table->string('rent_or_sell');
            $table->string('address');
            $table->float('area');
            $table->integer('numberofRooms')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('descreption');
            $table->float('price')->nullable();
            $table->float('monthlyRent')->nullable();
            $table->float('price_square_meter')->nullable();
            $table->float('rent_square_meter')->nullable();


            $table->rememberToken();

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
        Schema::dropIfExists('property_special');
    }
};
