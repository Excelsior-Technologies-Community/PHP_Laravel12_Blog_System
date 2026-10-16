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
        // 1. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#4361ee');
            $table->string('icon', 50)->default('bi-folder');
            $table->timestamps();
        });

        // 2. Tags Table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Add category_id and slug to posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->string('slug')->nullable()->after('title');
        });

        // 4. Post-Tag Pivot Table
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'slug']);
        });
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};
