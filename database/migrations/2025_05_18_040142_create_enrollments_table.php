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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('practicum_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('study_plan_path');
            $table->string('transcript_path');
            $table->string('photo_path');

            // For Next if Needed [BARU] Kolom status untuk alur kerja persetujuan
            // $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');

            // For Next if Needed [BARU] Kolom untuk alasan penolakan (bisa null)
            // $table->text('rejection_reason')->nullable();

            // For Next if Needed [BARU] Kolom audit (bisa null)
            // $table->foreignId('approved_by')->nullable()->constrained('users');
            // $table->timestamp('approved_at')->nullable();

            $table->float('final_active_score')->nullable();
            $table->float('final_report_score')->nullable();
            $table->float('final_score')->nullable();
            $table->string('final_grade')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'practicum_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
