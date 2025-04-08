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
        Schema::create('article_contributor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('contributor_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable(); // e.g., "Photography", "Illustration", "Graphics"
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            // Unique combination of article, contributor and role
            $table->unique(['article_id', 'contributor_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_contributor');
    }
};
