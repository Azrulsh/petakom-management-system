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
        Schema::create('activity_reports', function (Blueprint $table) {
           $table->id();

            // Related Proposal
            $table->foreignId('proposal_id')
                ->nullable()
                ->constrained('proposals')
                ->nullOnDelete();

            // Activity Information
            $table->string('activity_title');
            $table->date('activity_date');
            $table->string('venue')->nullable();

            // Actual Results
            $table->unsignedInteger('actual_attendance')->default(0);
            $table->decimal('actual_expenditure', 12, 2)->default(0);

            // Activity Evaluation
            $table->text('summary');
            $table->text('achievements')->nullable();
            $table->text('challenges')->nullable();
            $table->text('recommendations')->nullable();

            // Report Information
            $table->string('prepared_by');
            $table->string('status', 30)->default('draft');

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
        Schema::dropIfExists('activity_reports');
    }
};
