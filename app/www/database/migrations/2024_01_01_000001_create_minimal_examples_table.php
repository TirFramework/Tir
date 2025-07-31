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
        Schema::create('minimal_examples', function (Blueprint $table) {
            $table->id();

            // Fields that will be auto-included in $fillable
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('user_email')->nullable(); // Auto-label: "User Email"
            $table->boolean('is_active')->default(true);

            // Field that will be excluded from $fillable (fillable(false))
            $table->text('internal_notes')->nullable();

            // Note: computed_value is virtual, so no database column needed
            // Note: created_at is handled by timestamps()

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minimal_examples');
    }
};
