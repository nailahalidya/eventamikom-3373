<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {

            $table->id();

            // User login (boleh null jika guest)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Nama guest
            $table->string('guest_name')->nullable();

            // Review anonim
            $table->boolean('is_anonymous')->default(false);

            // Event
            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            // Rating
            $table->tinyInteger('rating');

            // Isi review
            $table->text('review');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};