<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundryServicesTable extends Migration
{
    public function up()
    {
        Schema::create('laundry_services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['kiloan', 'non-kiloan']);
            $table->integer('price_reguler');
            $table->integer('price_express');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('laundry_services');
    }
}
