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
        Schema::create(config('tag.tables.tag'), function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique()->index();

            $table->string('type')->nullable()->index();
            $table->integer('ordering')->nullable()->index();

            $table->timestamps();
        });

        cache()->forget('tag');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('tag.tables.tag'));

        cache()->forget('tag');
    }
};
