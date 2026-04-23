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
        Schema::table('route_stops', function (Blueprint $table) {
            $table->string('slug', 100)->nullable()->after('name');
            $table->string('icon_slug', 100)->nullable()->after('slug');
            $table->unique(['route_segment', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('route_stops', function (Blueprint $table) {
            $table->dropUnique('route_stops_route_segment_slug_unique');
            $table->dropColumn(['slug', 'icon_slug']);
        });
    }
};
