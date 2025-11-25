<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {

        // Ubah kolom payment_status menjadi ENUM
        $table->enum('payment_status', [
            'pending',
            'waiting_verification',
            'paid',
            'cancelled',
            'timeout'
        ])->default('pending')->change();

        // Tambahkan kolom baru
        $table->timestamp('cancelled_at')->nullable()->after('payment_status');
        $table->string('cancelled_by')->nullable()->after('cancelled_at');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        // Balikkan perubahan saat rollback
        $table->string('payment_status')->change();
        $table->dropColumn(['cancelled_at', 'cancelled_by']);
    });
}

};
