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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('hero_image_path')->nullable();
            $table->string('hero_headline')->nullable();
            $table->text('google_maps_url')->nullable();
            $table->json('gallery_paths')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'hero_image_path',
                'hero_headline',
                'google_maps_url',
                'gallery_paths',
            ]);
        });
    }
};
