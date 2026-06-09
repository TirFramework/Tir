<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates user_roles and user_role_user tables for role-based access control.
     */
    public function up(): void
    {
        // Create user_roles table
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('type')->default('admin')->index(); // admin, partner, employer
            $table->json('permissions')->nullable(); // Permission matrix
            $table->timestamps();
        });

        // Create user_role_user pivot table (many-to-many relationship)
        Schema::create('user_role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('user_roles')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate role assignments
            $table->unique(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role_user');
        Schema::dropIfExists('user_roles');
    }
};
