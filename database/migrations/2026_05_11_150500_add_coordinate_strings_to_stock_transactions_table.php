<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->string('coordinates_from')->nullable()->after('longitude');
            $table->string('coordinates_to')->nullable()->after('longitude_to');
        });
    }

    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['coordinates_from', 'coordinates_to']);
        });
    }
};
