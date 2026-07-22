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
        Schema::table('posts', function (Blueprint $table) {

            // Post Status
            $table->enum('status', ['draft', 'published'])
                ->default('draft')
                ->after('image');

            // Featured Post
            $table->boolean('is_featured')
                ->default(false)
                ->after('status');

            // Reading Time
            $table->integer('reading_time')
                ->default(1)
                ->after('is_featured');

            // Soft Delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->dropColumn('status');
            $table->dropColumn('is_featured');
            $table->dropColumn('reading_time');

            $table->dropSoftDeletes();
        });
    }
};
