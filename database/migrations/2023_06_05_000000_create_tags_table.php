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

            $table->string('type')->index();
            $table->integer('ordering')->default(0)->index();
            $table->boolean('status')->default(true)->index();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('tag.tables.tag'));
    }
};
