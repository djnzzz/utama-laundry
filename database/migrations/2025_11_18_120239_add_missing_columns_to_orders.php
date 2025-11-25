<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            // Tambah kolom identitas pelanggan
            if (!Schema::hasColumn('orders', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('name');
            }

            // Tambah kolom dari laundry service
            if (!Schema::hasColumn('orders', 'service_name')) {
                $table->string('service_name')->nullable()->after('service_code');
            }
            if (!Schema::hasColumn('orders', 'service_type')) {
                $table->string('service_type')->nullable()->after('service_name');
            }

            // Tambah total harga
            if (!Schema::hasColumn('orders', 'total_harga')) {
                $table->unsignedInteger('total_harga')->nullable()->after('final_price');
            }

            // Tambah pakaian dalam
            if (!Schema::hasColumn('orders', 'pakaian_dalam')) {
                $table->enum('pakaian_dalam', ['Ya', 'Tidak'])->nullable()->after('total_harga');
            }
            if (!Schema::hasColumn('orders', 'jumlah_pakaian_dalam')) {
                $table->unsignedInteger('jumlah_pakaian_dalam')->nullable()->after('pakaian_dalam');
            }

            // Tambah order_date
            if (!Schema::hasColumn('orders', 'order_date')) {
                $table->dateTime('order_date')->nullable()->after('jumlah_pakaian_dalam');
            }

            // Tambah kolom status (controller pakai "status" bukan "status_cucian")
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('payment_status');
            }

            // Penyesuaian nama kolom agar cocok dengan controller
            if (Schema::hasColumn('orders', 'item_count') && !Schema::hasColumn('orders', 'jumlah_item')) {
                $table->renameColumn('item_count', 'jumlah_item');
            }

            if (Schema::hasColumn('orders', 'estimasi_weight') && !Schema::hasColumn('orders', 'estimasi_berat')) {
                $table->renameColumn('estimasi_weight', 'estimasi_berat');
            }

        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

            // Hapus kolom tambahan
            $dropCols = [
                'name',
                'phone',
                'service_name',
                'service_type',
                'total_harga',
                'pakaian_dalam',
                'jumlah_pakaian_dalam',
                'order_date',
                'status'
            ];

            foreach ($dropCols as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Kembalikan nama kolom jika sempat berubah
            if (Schema::hasColumn('orders', 'jumlah_item') && !Schema::hasColumn('orders', 'item_count')) {
                $table->renameColumn('jumlah_item', 'item_count');
            }

            if (Schema::hasColumn('orders', 'estimasi_berat') && !Schema::hasColumn('orders', 'estimasi_weight')) {
                $table->renameColumn('estimasi_berat', 'estimasi_weight');
            }
        });
    }
};
