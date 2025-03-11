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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('image');
            $table->json('images');
            $table->string('country');
            $table->string('address');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('surface')->nullable();
            $table->decimal('price', 10, 2);
            $table->text('type');
            $table->boolean('is_available')->default(true);
            $table->string('user_username');
            // Add foreign key constraint
            $table->foreign('user_username')
                  ->references('username')
                  ->on('users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
