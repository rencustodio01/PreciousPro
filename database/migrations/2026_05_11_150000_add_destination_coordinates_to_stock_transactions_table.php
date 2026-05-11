<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->decimal('latitude_to', 10, 8)->nullable()->after('longitude');
            $table->decimal('longitude_to', 11, 8)->nullable()->after('latitude_to');
        });
    }

    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['latitude_to', 'longitude_to']);
        });
    }
};
