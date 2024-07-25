<?php

namespace JobMetric\Tag\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('tag.tables.tag_relation'), function (Blueprint $table) {
            $table->foreignId('tag_id')->index()->constrained(config('tag.tables.tag'))->cascadeOnUpdate()->cascadeOnDelete();

            $table->morphs('taggable');

            $table->unique(['tag_id', 'taggable_type', 'taggable_id'], 'TAGGABLE_UNIQUE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('tag.tables.tag_relation'));
    }
};
