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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();

            // Proposal Information
            $table->string('title');
            $table->string('activity_category', 100)->nullable();
            $table->text('background')->nullable();
            $table->text('objectives');

            // Activity Details
            $table->date('proposed_date');
            $table->date('end_date')->nullable();
            $table->string('venue');

            // Target and Budget
            $table->unsignedInteger('target_participants')->default(0);
            $table->decimal('estimated_budget', 12, 2)->default(0);

            // Applicant Information
            $table->string('prepared_by');

            // Approval Information
            $table->string('status', 30)->default('draft')->index();
            $table->text('review_remarks')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();

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
        Schema::dropIfExists('proposals');
    }
};
