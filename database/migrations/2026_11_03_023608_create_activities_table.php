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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Activity Creator
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Optional Approved Proposal
            $table->foreignId('proposal_id')
                ->nullable()
                ->unique()
                ->constrained('proposals')
                ->restrictOnDelete();

            // Activity Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('activity_category', 100)->nullable();

            // Activity Schedule
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('venue');

            // Activity Management
            $table->string('status', 30)->default('upcoming')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
