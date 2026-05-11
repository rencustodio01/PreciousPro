<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transactions', 'latitude_from')) {
                $table->dropColumn('latitude_from');
            }
            if (Schema::hasColumn('stock_transactions', 'longitude_from')) {
                $table->dropColumn('longitude_from');
            }
            if (Schema::hasColumn('stock_transactions', 'latitude_to')) {
                $table->dropColumn('latitude_to');
            }
            if (Schema::hasColumn('stock_transactions', 'longitude_to')) {
                $table->dropColumn('longitude_to');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_transactions', 'latitude_from')) {
                $table->decimal('latitude_from', 10, 8)->nullable()->after('coordinates_from');
            }
            if (! Schema::hasColumn('stock_transactions', 'longitude_from')) {
                $table->decimal('longitude_from', 11, 8)->nullable()->after('latitude_from');
            }
            if (! Schema::hasColumn('stock_transactions', 'latitude_to')) {
                $table->decimal('latitude_to', 10, 8)->nullable()->after('coordinates_to');
            }
            if (! Schema::hasColumn('stock_transactions', 'longitude_to')) {
                $table->decimal('longitude_to', 11, 8)->nullable()->after('latitude_to');
            }
        });
    }
};
