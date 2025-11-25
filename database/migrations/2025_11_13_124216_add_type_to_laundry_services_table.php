<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('laundry_services', function (Blueprint $table) {
        $table->enum('type', ['kiloan', 'non-kiloan'])->default('kiloan')->after('name');
    });
}

public function down()
{
    Schema::table('laundry_services', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}

};
