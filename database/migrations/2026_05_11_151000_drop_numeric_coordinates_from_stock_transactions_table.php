<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transactions', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('stock_transactions', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('stock_transactions', 'latitude_to')) {
                $table->dropColumn('latitude_to');
            }
            if (Schema::hasColumn('stock_transactions', 'longitude_to')) {
                $table->dropColumn('longitude_to');
            }
        });
    }

    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_transactions', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('location_to');
            }
            if (! Schema::hasColumn('stock_transactions', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (! Schema::hasColumn('stock_transactions', 'latitude_to')) {
                $table->decimal('latitude_to', 10, 8)->nullable()->after('longitude');
            }
            if (! Schema::hasColumn('stock_transactions', 'longitude_to')) {
                $table->decimal('longitude_to', 11, 8)->nullable()->after('latitude_to');
            }
        });
    }
};
