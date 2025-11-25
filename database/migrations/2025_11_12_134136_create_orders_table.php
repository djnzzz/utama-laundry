<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_sn')->nullable()->unique(); // akan diisi setelah create
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('service_code'); // refer ke laundry_services.code
            $table->enum('paket', ['Reguler','Express']);
            $table->enum('type', ['kiloan','non-kiloan']); // optional helper
            $table->decimal('estimasi_weight', 6, 2)->nullable(); // kg
            $table->unsignedInteger('estimasi_price')->nullable(); // rupiah
            $table->decimal('final_weight', 6, 2)->nullable();
            $table->unsignedInteger('final_price')->nullable();
            $table->unsignedInteger('underwear_count')->default(0);
            $table->unsignedInteger('item_count')->default(0); // untuk selimut/sprei/boneka
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('status_cucian')->default('baru'); // baru, dikerjakan, siap ambil, dll
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
