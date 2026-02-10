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
        Schema::create('campaign_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('link_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('channel_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_links');
    }
};
