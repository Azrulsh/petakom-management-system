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
    public function up(): void
    {
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();

            // User who created the bulletin
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Bulletin Information
            $table->string('title');
            $table->text('content');

            // Bulletin Category: PETAKOM or Faculty
            $table->string('type', 20);

            // Optional attachment (PDF or image)
            $table->string('attachment')->nullable();

            // Publication status
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Indexes for filtering
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulletins');
    }
};
