<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();

            // Core listing info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['sale', 'rent', 'both'])->default('sale');
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold', 'rented'])
                  ->default('pending');
            $table->text('rejection_reason')->nullable();

            // Pricing
            $table->decimal('price', 15, 2);
            $table->string('price_period')->nullable()->comment('monthly|yearly — for rent');

            // Specs
            $table->unsignedTinyInteger('bedrooms')->default(0);
            $table->unsignedTinyInteger('bathrooms')->default(0);
            $table->decimal('area_sqm', 10, 2)->nullable();

            // Location
            $table->string('address');
            $table->string('district', 60);
            $table->string('sector', 60)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Media & metadata
            $table->json('features')->nullable()->comment('["parking","pool","garden",…]');
            $table->json('images')->nullable()->comment('Array of storage paths');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Indexes for common filter queries
            $table->index(['status', 'type']);
            $table->index(['district', 'status']);
            $table->index(['price', 'status']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
