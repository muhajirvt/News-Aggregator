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
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('source_id')->unsigned()->constrained()->onDelete('cascade');
                $table->foreignId('author_id')->unsigned()->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->unsigned()->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->dateTime('published_at');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
