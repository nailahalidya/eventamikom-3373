<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete()->after('event_id');
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->boolean('coupon_used_counted')->default(false)->after('is_stock_released');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'coupon_used_counted']);
            $table->dropConstrainedForeignId('coupon_id');
        });
    }
};
