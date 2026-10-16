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
        // 1. Comments Table (Supports Nested Threaded Replies)
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->string('author_name');
            $table->string('author_email');
            $table->text('content');
            $table->string('status', 20)->default('approved'); // approved, pending, rejected
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });

        // 2. Reactions Table (Love, Clap, Insightful)
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('type', 20); // love, clap, insightful
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->timestamps();

            $table->index(['post_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('comments');
    }
};
