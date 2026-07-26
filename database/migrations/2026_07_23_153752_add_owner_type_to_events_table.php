<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'owner_type')) {
                $table->enum('owner_type', ['admin', 'organizer'])->default('admin')->after('stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('owner_type');
        });
    }
};