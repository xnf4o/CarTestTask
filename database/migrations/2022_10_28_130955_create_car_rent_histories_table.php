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
        Schema::create('car_rent_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('The user that rents the car')->constrained();
            $table->foreignId('car_id')->comment('The car that the user rents')->constrained();
            $table->dateTime('rented_at')->comment('The date and time the car was rented');
            $table->dateTime('returned_at')->nullable()->comment('The date and time the car was returned');
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
        Schema::dropIfExists('car_rent_histories');
    }
};
