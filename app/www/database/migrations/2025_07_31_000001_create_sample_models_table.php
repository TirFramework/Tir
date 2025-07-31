<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_models', function (Blueprint $table) {
            $table->id();

            // Text Fields
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();

            // Email and Password
            $table->string('email')->unique()->nullable();
            $table->string('website_url')->nullable();

            // Numbers
            $table->integer('priority')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);

            // Boolean
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);

            // Dates
            $table->date('publish_date')->nullable();
            $table->datetime('start_datetime')->nullable();
            $table->datetime('end_datetime')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();

            // Select/Enum Fields
            $table->enum('status', ['draft', 'published', 'archived', 'deleted'])->default('draft');
            $table->enum('type', ['basic', 'premium', 'enterprise'])->default('basic');
            $table->string('category')->nullable();
            $table->string('tags')->nullable(); // JSON will be cast in model

            // File Uploads
            $table->string('avatar')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('document_file')->nullable();

            // Additional/JSON Fields
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->json('social_links')->nullable();

            // Foreign Keys (Relations)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('sample_models')->onDelete('cascade');

            // Soft Deletes
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'is_published']);
            $table->index(['user_id', 'created_at']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sample_models');
    }
};
