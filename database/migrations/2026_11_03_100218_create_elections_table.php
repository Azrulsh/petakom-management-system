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
        Schema::create('elections', function (Blueprint $table) {
            $table->id();

            // Election Information
            $table->string('title');
            $table->string('academic_session', 20);
            $table->text('description')->nullable();

            // Candidate Registration Period
            $table->dateTime('nomination_start');
            $table->dateTime('nomination_end');

            // Election Status
            $table->string('status', 30)->default('draft');

            // Election Creator
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

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
        Schema::dropIfExists('elections');
    }
};
