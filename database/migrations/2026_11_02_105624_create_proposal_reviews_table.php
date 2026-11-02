<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_reviews', function (Blueprint $table) {

            $table->id();

            $table->foreignId('proposal_id')
                ->constrained('proposals')
                ->restrictOnDelete();

            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('decision', 20);

            $table->text('remarks')->nullable();

            $table->timestamp('reviewed_at');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_reviews');
    }
};
